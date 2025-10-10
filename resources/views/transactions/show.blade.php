<div class="container mt-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Detail Transaksi</h4>
            <a href="{{ route('transactions.index') }}" class="btn btn-light btn-sm">Kembali</a>
        </div>

        <div class="card-body">
            {{-- Informasi Transaksi --}}
            <h5 class="mb-3">Informasi Umum</h5>
            <table class="table table-borderless">
                <tr>
                    <th>Kode Transaksi</th>
                    <td>{{ $transaction->transaction_code }}</td>
                </tr>
                <tr>
                    <th>Tanggal Transaksi</th>
                    <td>{{ $transaction->transaction_date }}</td>
                </tr>
                <tr>
                    <th>Kasir</th>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Pelanggan</th>
                    <td>{{ $transaction->customer->name ?? 'Umum' }}</td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ ucfirst($transaction->payment_method) }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($transaction->status == 'success')
                        <span class="badge bg-success">Sukses</span>
                        @else
                        <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </td>
                </tr>
            </table>

            {{-- Detail Barang --}}
            <h5 class="mt-4 mb-3">Barang yang Dibeli</h5>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Harga Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->product->name }}</td>
                        <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Ringkasan Pembayaran --}}
            <div class="row justify-content-end mt-4">
                <div class="col-md-5">
                    <table class="table table-borderless">
                        <tr>
                            <th>Diskon</th>
                            <td>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Total Bayar</th>
                            <td><strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <th>Jumlah Dibayar</th>
                            <td>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Kembalian</th>
                            <td>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Tombol Cetak / Kembali --}}
            <div class="d-flex justify-content-end mt-3">
                <a href="" class="btn btn-success me-2">
                    <i class="bi bi-printer"></i> Cetak Nota
                </a>
            </div>
        </div>
    </div>
</div>