@extends('layouts.app', ['detail' => $detail ?? NULL])

@section('css-header')
<!-- Css -->
<link rel="stylesheet" href="{{ asset('vendors/dataTable/dataTables.min.css') }}" type="text/css">
@endsection

@section('content')
    @if(isset($filters))
        <x-content.DataFilter :filters=$filters />
    @endif
    <x-content.CardList :options=$view_options :contents="$contents" :datas="$datas" />
    <!-- Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Keranjang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="cartModalBody"></div>
                    <!-- Konten daftar barang di keranjang akan ditambahkan melalui JavaScript -->
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label for="Total price">Total price</label>
                        </div>
                        <div class="col-md-6">
                            <span id="totalPrice"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <form id="cartForm" action="#" method="post">
                        <button type="submit" class="btn btn-primary btn-block btn-sm">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-footer')
<!-- Javascript -->
<script src="{{ asset('vendors/dataTable/jquery.dataTables.min.js') }}"></script>
<!-- Bootstrap 4 and responsive compatibility -->
<script src="{{ asset('vendors/dataTable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendors/dataTable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/examples/datatable.js') }}"></script>
<script>
    $(document).ready(function() {
        // Event yang memicu tampilan modal dan memasukkan data ke dalam modal
        $('.dynamic-modal-trigger').on('click', function() {
            var jsonData = $(this).data('json');
            var judul = "Product Detail";
            var price = `<p>${jsonData.currency} ${new Intl.NumberFormat('de-DE').format(jsonData.price)},-</p>`
            if (jsonData.discount > 0) {
                price = `<p><del>${jsonData.currency} ${new Intl.NumberFormat('de-DE').format(jsonData.price)},-</del></p>
                    <p>${jsonData.currency} ${new Intl.NumberFormat('de-DE').format(jsonData.price - (jsonData.price * (jsonData.discount / 100)))},-</p>`
            }
            var isi = `<div class="row"><div class="col-md-6">
            <img class="w-100" src=${jsonData.image} alt="no-image" /></div>
            <div class="col-md-6">
                <h6>${jsonData.product_name}</h6>
                ${price}
                <p>Dimension : ${jsonData.dimensions}</p>
                <p>Price Unit : ${jsonData.unit}</p>
            </div>
            `;

            // Isi modal dengan data yang diambil dari atribut data-json
            $('#dynamicModalLabel').text(judul);
            $('#dynamicModalBody').html(isi);

            // Munculkan modal
            $('#dynamicModal').modal('show');
        });

        $('.add-to-cart').on('click', function() {
            // Ambil json dari data button
            var jsonData = $(this).data('json');

            // Ambil data sebelumnya dari session storage
            var data = sessionStorage.getItem('productData');
            var productList = [];

            if (data) {
                // Jika data sebelumnya ada, konversi menjadi array
                productList = JSON.parse(data);
            }

            // Ambil informasi produk dari tombol yang ditekan
            var productCode = jsonData.product_code;
            var qty = 1;

            // Cek apakah produk sudah ada dalam daftar
            var existingProduct = productList.find(function(product) {
                return product.product_code === productCode;
            });

            if (existingProduct) {
                // Jika produk sudah ada, tambahkan kuantitas
                existingProduct.qty += qty;
            } else {
                // Jika produk belum ada, tambahkan data produk baru
                productList.push({ product_code: productCode, qty: qty, detail: jsonData });
            }

            // Simpan daftar produk ke session storage
            sessionStorage.setItem('productData', JSON.stringify(productList));

            // Perbarui teks pada tombol dengan jumlah product_code yang ada
            updateCartButton();
        });

        // Fungsi untuk memperbarui teks pada tombol dengan jumlah product_code yang ada di session storage
        function updateCartButton() {
            var data = sessionStorage.getItem('productData');
            var productList = [];

            if (data) {
                productList = JSON.parse(data);
            }

            var uniqueProductCount = 0;
            var productCodes = [];

            for (var i = 0; i < productList.length; i++) {
                var productCode = productList[i].product_code;

                if (!productCodes.includes(productCode)) {
                    productCodes.push(productCode);
                    uniqueProductCount++;
                }
            }

            // Perbarui teks pada tombol dengan jumlah product_code yang ada
            var cartButton = document.getElementById('cartButton');
            cartButton.innerHTML = 'Keranjang (' + uniqueProductCount + ')';
        }

        // Fungsi untuk memperbarui nilai qty di session storage berdasarkan product_code
        function updateQty(productCode, newQty) {
            var data = sessionStorage.getItem('productData');
            var productList = [];

            if (data) {
                productList = JSON.parse(data);
            }

            // Cari indeks produk berdasarkan product_code
            var productIndex = productList.findIndex(function(item) {
                return item.product_code === productCode;
            });

            if (productIndex !== -1) {
                // Update nilai qty
                productList[productIndex].qty = newQty;
                // Simpan kembali ke session storage
                sessionStorage.setItem('productData', JSON.stringify(productList));
                // Perbarui teks pada tombol dengan jumlah product_code yang ada
                updateCartButton();
            }
        }

        // Panggil fungsi updateCartButton untuk memperbarui teks tombol saat halaman dimuat
        updateCartButton();

        // Event yang dipicu saat modal ditampilkan
        $('#cartModal').on('shown.bs.modal', function() {
            var data = sessionStorage.getItem('productData');
            var productList = [];

            if (data) {
                productList = JSON.parse(data);
            }
            // Reset $('#cartModalBody') html 
            $('#cartModalBody').html("")
            var modalBody = ""
            var totalPrice = 0
            for (var i = 0; i < productList.length; i++) {
                var jsonData = productList[i].detail;
                var qty = productList[i].qty;

                var price = jsonData.price
                if (jsonData.discount > 0) {
                    price = jsonData.price - (jsonData.price * (jsonData.discount / 100))
                }
                price *= qty
                totalPrice += price
                var priceWrapper = `<p>${jsonData.currency} ${new Intl.NumberFormat('de-DE').format(price)},-</p>`
                modalBody = `<div class="row mb-2"><div class="col-md-6">
                <img class="w-100" src=${jsonData.image} alt="no-image" /></div>
                <div class="col-md-6">
                    <h6>${jsonData.product_name}</h6>
                    <div class="input-group mb-3">
                        <input class="form-control qty-input" data-product-code="${jsonData.product_code}" name="qty" min="1" type="number" value=${qty} placeholder="Qty" aria-label="Qty">
                        <div class="input-group-append">
                            <span class="input-group-text">${jsonData.unit}</span>
                        </div>
                    </div>
                    <div class="price-wrapper">${priceWrapper}</div>
                </div>
                `;
                $('#cartModalBody').append(modalBody)
                $('#totalPrice').html(`${jsonData.currency} ${new Intl.NumberFormat('de-DE').format(totalPrice)},-`)
            }
        });

        // Menambahkan event listener pada input field qty
        $(document).on('change', '.qty-input', function() {
            var productCode = $(this).data('product-code');
            var newQty = parseInt($(this).val());
            updateQty(productCode, newQty);

            // Mendapatkan data produk dari session storage berdasarkan product_code
            var data = sessionStorage.getItem('productData');
            var productList = [];

            if (data) {
                productList = JSON.parse(data);
            }

            // Cari produk berdasarkan product_code
            var product = productList.find(function(item) {
                return item.product_code === productCode;
            });

            if (product) {
                // Mengupdate nilai price dengan qty yang baru
                var price = product.detail.price;
                if (product.detail.discount > 0) {
                    price = product.detail.price - (product.detail.price * (product.detail.discount / 100));
                }
                price *= newQty;

                // Mengubah tampilan harga pada modal dengan harga yang baru
                var priceWrapper = $(this).closest('.col-md-6').find('.price-wrapper');
                priceWrapper.html(`<p>${product.detail.currency} ${new Intl.NumberFormat('de-DE').format(newPrice)},-</p>`);
            }
        });

        // Event yang menangani submit form
        $('#cartForm').on('submit', function(e) {
            e.preventDefault();
            // Mengirimkan form atau melakukan operasi lain yang Anda inginkan
            var data = sessionStorage.getItem('productData');
            var requestData = {
                'username': '{{ Auth::user()->username }}',
                'data': JSON.parse(data)
            }
            $.ajax({
                url: "{{ route('api.transactions.store') }}", // Ganti dengan URL API yang sesuai
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(requestData),
                success: function(response) {
                // Lakukan sesuatu dengan respons dari API
                alert(response.message)
                console.log(response);
            },
            error: function(error) {
                // Tangani kesalahan jika terjadi
                alert(error.message)
                console.error(error);
            }
            });
            // Setelah itu, hapus data keranjang dari session storage
            sessionStorage.removeItem('productData');
            // Perbarui teks pada tombol dengan jumlah product_code yang ada
            updateCartButton();
            // Tutup modal
            $('#cartModal').modal('hide');
        });
    });

</script>
@endsection