<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Transactions;
use App\TransactionDetail;
use App\Products;
use App\Customers;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
class TransactionsController extends Controller
{

    public function index()
    {
        $transactions = Transactions::with(['customer', 'user'])->get();
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ambil transaksi terakhir
        $lastTransaction = Transactions::latest('transaction_id')->first();
        $nextId = $lastTransaction ? $lastTransaction->transaction_id + 1 : 1;

        // // Format invoice: INV-YYYYMMDD-XXX
        $invoiceCode = 'INV-' . date('Ymd') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $customers = Customers::all();
        $products = Products::all();
        return view('transactions.create', compact('customers', 'products', 'invoiceCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'     => 'nullable|exists:customers,customer_id',
            'payment_method'  => 'required|string',
            'paid_amount'     => 'required|numeric',
            'cart_items'      => 'required',
            'grand_total'     => 'required|numeric',
            'change_amount'   => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {

            // Simpan transaksi utama
            $transaction = Transactions::create([
                
                'customer_id'       => $request->customer_id,
                'user_id'           => auth()->id() ?? 1,
                'payment_method'    => $request->payment_method,
                'discount'          => $request->discount_hidden ?? 0,
                'total_amount'      => $request->grand_total,
                'paid_amount'       => $request->paid_amount,
                'change_amount'     => $request->change_amount,
                'transaction_date'  => now(),
                'status'            => $request->payment_method == 'cash' ? 'success' : 'pending'
            ]);

            //buat kode invoice berdasarkan id yang sudah pasti ada
            $transaction->transaction_code = 'INV-' . date('Ymd') . '-' . str_pad($transaction->transaction_id, 3, '0', STR_PAD_LEFT);
            $transaction->save();
            // Ambil semua item cart
            $items = json_decode($request->cart_items, true);

            foreach ($items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->transaction_id,
                    'product_id'     => $item['id'],
                    'price'          => $item['price'],
                    'quantity'       => $item['qty'],
                    'subtotal'       => $item['price'] * $item['qty'],
                ]);

                // Kurangi stok produk
                Products::where('product_id', $item['id'])
                    ->decrement('stock', $item['qty']);
            }

            // //  Jika metode pembayaran adalah transfer, kirim ke Midtrans
            if ($request->payment_method == 'transfer') {
                Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                Config::$isProduction = false; // ubah ke true jika sudah live
                Config::$isSanitized = true;
                Config::$is3ds = true;

                // Ambil item dari keranjang
                $itemDetails = collect($items)->map(function ($i) {
                    return [
                        'id' => $i['id'],
                        'price' => (int)$i['price'],
                        'quantity' => (int)$i['qty'],
                        'name' => $i['name'],
                    ];
                })->toArray();

                // Hitung total harga semua item
                $totalItems = collect($itemDetails)->sum(function ($i) {
                    return $i['price'] * $i['quantity'];
                });

                // Jika ada diskon, tambahkan sebagai item negatif
                if ($transaction->discount > 0) {
                    $itemDetails[] = [
                        'id' => 'DISCOUNT',
                        'price' => -(int)$transaction->discount,
                        'quantity' => 1,
                        'name' => 'Diskon',
                    ];
                }

                // Hitung total akhir untuk gross_amount
                $grossAmount = $totalItems - ($transaction->discount ?? 0);

                $payload = [
                    'transaction_details' => [
                        'order_id' => $transaction->transaction_code,
                        'gross_amount' => (int)$grossAmount, // harus sama dengan jumlah total item
                    ],
                    'customer_details' => [
                        'first_name' => optional($transaction->customer)->name ?? 'Guest',
                        'email' => optional($transaction->customer)->email ?? 'noemail@example.com',
                        'phone' => optional($transaction->customer)->phone ?? '08123456789',
                    ],
                    'item_details' => $itemDetails,
                ];
                $snapToken = Snap::getSnapToken($payload);

                if (!$snapToken) {
                    throw new \Exception('Gagal membuat Snap Token dari Midtrans');
                }

                DB::commit();

                // Simpan token di session dan arahkan ke halaman pembayaran
                session(['snapToken' => $snapToken]);
                return redirect()->route('transactions.payment', [
                    'transaction_code' => $transaction->transaction_code
                ]);
            }

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function payment($transaction_code)
    {
        $transaction = Transactions::where('transaction_code', $transaction_code)->firstOrFail();
        $snapToken = session('snapToken');

        return view('transactions.payment', compact('transaction', 'snapToken'));
    }

    public function callback(Request $request)
    {
        // Jika callback datang dari JS (bukan dari server Midtrans)
        if ($request->has('order_id') && $request->has('transaction_status')) {
            $transaction = Transactions::where('transaction_code', $request->order_id)->first();

            if (!$transaction) {
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            if ($request->transaction_status === 'settlement' || $request->transaction_status === 'capture') {
                $transaction->status = 'success';
            } elseif ($request->transaction_status === 'pending') {
                $transaction->status = 'pending';
            } else {
                $transaction->status = 'failed';
            }

            $transaction->save();

            return response()->json(['message' => 'Status transaksi diperbarui ke ' . $transaction->status]);
        }

        // Jika callback datang dari Midtrans server (webhook)
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $notification = new Notification();

        $status = $notification->transaction_status;
        $orderId = $notification->order_id;

        $transaction = Transactions::where('transaction_code', $orderId)->first();

        if ($transaction) {
            if ($status == 'settlement' || $status == 'capture') {
                $transaction->status = 'success';
            } elseif ($status == 'pending') {
                $transaction->status = 'pending';
            } else {
                $transaction->status = 'failed';
            }
            $transaction->save();
        }

        return response()->json(['message' => 'Callback processed successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transactions::with(['customer', 'user', 'details.product'])->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Hapus dulu semua detail transaksi yang berhubungan
            TransactionDetail::where('transaction_id', $id)->delete();

            // Baru hapus data transaksinya
            Transactions::where('transaction_id', $id)->delete();

            DB::commit();

            return redirect()->route('transactions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transactions.index');
        }
    }
}