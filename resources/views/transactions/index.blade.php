<h3>Daftar Transaksi</h3>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Transaksi</th>
            <th>Customer</th>
            <th>Kasir</th>
            <th>Total</th>
            <th>Status</th>
            <th>Metode Pemabayaran</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $trx)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $trx->transaction_code }}</td>
            <td>{{ $trx->customer->name ?? '-' }}</td>
            <td>{{ $trx->user->name ?? '-' }}</td>
            <td>{{ number_format($trx->total_amount, 0, ',', '.') }}</td>
            <td>{{ $trx->status }}</td>
            <td>{{$trx->payment_method}}</td>
            <td>{{ $trx->transaction_date }}</td>
            <td>
                
                <form action="{{ route('transactions.destroy', $trx->transaction_id) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <a href="{{ route('transactions.print', $trx->transaction_id) }}">Print Struk</a>
                    <a href="{{ route('transactions.show', $trx->transaction_id) }}">Detail</a>
                    <button type="submit" onclick="return confirm('Yakin hapus transaksi ini?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>