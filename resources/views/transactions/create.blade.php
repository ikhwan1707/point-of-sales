<div class="card">
    <div class="card-header bg-primary text-white">
        Form Create Transaction
    </div>
    <div class="card-body">

        <form action="{{route('transactions.store')}}" method="POST">
            {{ csrf_field() }}

            <!-- Invoice Code -->
            <div class="form-group mb-3">
                <label for="transaction_code">Nomor Invoice</label>
                <input type="text" class="form-control" name="transaction_code" value="{{ $invoiceCode }}" readonly>
            </div>

            {{-- Pilih Customer --}}
            <div class="mb-3">
                <label for="customer_id" class="form-label"><b>Customer (Opsional)</b></label>
                <select name="customer_id" id="customer_id" class="form-control" onchange="toggleMemberFields()">
                    <option value="">Pilih Customer</option>
                    @foreach($customers as $c)
                    <option value="{{ $c->customer_id }}" data-member="{{ $c->is_member }}">
                        {{ $c->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Metode Pembayaran --}}
            <div class="mb-3">
                <label for="payment_method" class="form-label"><b>Metode Pembayaran</b></label>
                <select name="payment_method" id="payment_method" class="form-control" required>
                    <option value="">Pilih Metode Pembayaran</option>
                    <option value="cash">Tunai</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>

            {{-- Pilih Produk --}}
            <div class="mb-3">
                <label for="product" class="form-label"><b>Produk</b></label>
                <select id="product" class="form-control">
                    <option value="">Pilih Produk</option>
                    @foreach($products as $p)
                    <option value="{{ $p->product_id }}" data-price="{{ $p->price }}" data-name="{{ $p->name }}"
                        data-image="{{ asset('storage/'.$p->image) }}">
                        {{ $p->name }} - Rp. {{ number_format($p->price, 0, ',', '.') }}
                    </option>
                    @endforeach
                </select>
            </div>

            <button type="button" class="btn btn-primary mb-3" onclick="addItem()">Tambah Item</button>

            {{-- Tabel Keranjang --}}
            <table class="table table-bordered" id="cart-table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            {{-- Total, Diskon, Grand Total, Bayar, Kembalian --}}
            <div class="mb-3">
                <label><b>Total Amount</b></label>
                <input type="text" id="total_amount" class="form-control" readonly>
            </div>

            <div id="member-fields" style="display: none;">
                <div class="mb-3">
                    <label><b>Diskon (Rp)</b></label>
                    <input type="text" id="discount" name="discount" class="form-control" readonly>
                    <small class="text-muted">Diskon 5% otomatis jika customer adalah member</small>
                </div>

                <div class="mb-3">
                    <label><b>Grand Total</b></label>
                    <input type="text" id="grand_total" class="form-control" readonly>
                </div>
            </div>

            <div class="mb-3">
                <label><b>Pembayaran</b></label>
                <input type="number" id="paid_amount" name="paid_amount" class="form-control"
                    oninput="hitungKembalian()">
            </div>

            <div class="mb-3">
                <label><b>Kembalian</b></label>
                <input type="text" id="change_amount" class="form-control" readonly>

            </div>

            <input type="hidden" name="cart_items" id="cart_items">
            <input type="hidden" name="grand_total" id="grand_total_hidden">
            <input type="hidden" name="discount_hidden" id="discount_hidden">
            <input type="hidden" name="change_amount" id="change_amount_hidden">

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Simpan Transaksi</button>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Batal</a>
            </div>

        </form>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let cart = [];

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR', 
            minimumFractionDigits: 0 
        }).format(angka);
    }

    function addItem() {
        let select = document.getElementById('product');
        let option = select.options[select.selectedIndex];
        if (!option.value) return;

        let id = option.value;
        let name = option.dataset.name;
        let price = parseFloat(option.dataset.price);
        let image = option.dataset.image;

        let existing = cart.find(item => item.id == id);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, image, qty: 1 });
        }

        renderCart();
    }

    function renderCart() {
        let tbody = document.querySelector("#cart-table tbody");
        tbody.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            let subTotal = item.price * item.qty;
            total += subTotal;

            tbody.innerHTML += `
                <tr>
                    <td><img src="${item.image}" width="50"></td>
                    <td>${item.name}</td>
                    <td>${formatRupiah(item.price)}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="changeQty(${index}, -1)">-</button>
                        ${item.qty}
                        <button type="button" class="btn btn-sm btn-secondary" onclick="changeQty(${index}, 1)">+</button>
                    </td>
                    <td>${formatRupiah(subTotal)}</td>
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${index})">Hapus</button></td>
                </tr>
            `;
        });

        document.getElementById('total_amount').value = formatRupiah(total);

        // simpan cart ke hidden input
        document.getElementById('cart_items').value = JSON.stringify(cart);
        hitungKembalian();
    }

    function changeQty(index, change) {
        cart[index].qty += change;
        if (cart[index].qty <= 0) {
            cart.splice(index, 1);
        }
        renderCart();
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function hitungKembalian(){
        let totalText = document.getElementById('total_amount').value.replace(/[Rp.\s]/g, '') || "0";
        let total = parseFloat(totalText);

        let customerSelect = document.getElementById('customer_id');
        let selectedOption = customerSelect.options[customerSelect.selectedIndex];
        let isMember = selectedOption ? selectedOption.dataset.member === "1" : false;

        let discount = isMember ? total * 0.05 : 0;
        let grandTotal = isMember ? (total - discount) : total;

        if (isMember) {
            document.getElementById('discount').value = formatRupiah(discount);
            document.getElementById('grand_total').value = formatRupiah(grandTotal);
        } else {
            document.getElementById('discount').value = "";
            document.getElementById('grand_total').value = "";
        }

        // simpan ke hidden input
        document.getElementById('discount_hidden').value = discount;
        document.getElementById('grand_total_hidden').value = grandTotal;

        let paid = parseFloat(document.getElementById('paid_amount').value) || 0;
        let change = paid - grandTotal;
        document.getElementById('change_amount').value = (change >= 0 ? formatRupiah(change) : formatRupiah(0));
        document.getElementById('change_amount_hidden').value = change;
    }

    function toggleMemberFields() {
        let customerSelect = document.getElementById('customer_id');
        let selectedOption = customerSelect.options[customerSelect.selectedIndex];
        let isMember = selectedOption ? selectedOption.dataset.member === "1" : false;
        
        let memberFields = document.getElementById('member-fields');
        
        if (isMember) {
        memberFields.style.display = 'block';
        } else {
        memberFields.style.display = 'none';
        }
        
        hitungKembalian(); // refresh perhitungan
    }
</script>