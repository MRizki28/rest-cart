<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container">
        <h2 class="text-center mt-3">Tambah Menu</h2>
        <div>
            <i class="fa-solid fa-cart-shopping"></i>
            <span id="cartCount"></span>
        </div>
        <form action="" id="formTambah" enctype="multipart/form-data">
            <div class=" mt-2">
                <label for="nama_menu">Menu</label>
                <input class="form-control" type="text" name="nama_menu" id="nama_menu">
            </div>
            <div class="mt-2">
                <label for="gambar_menu">Gambar</label>
                <input class="form-control" type="file" name="gambar_menu" id="gambar_menu">
            </div>
            <div class="mt-2">
                <label for="harga">Harga</label>
                <input class="form-control" type="text" name="harga" id="harga">
            </div>

            <div style="text-align: end;">
                <button type="submit" class="btn btn-primary mt-2 ">Submit</button>
            </div>
        </form>

        {{-- menu --}}
        <div class="row mt-5 border-0" id="menu-list">

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Detail Keranjang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="cartItems">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk menambahkan kuantitas -->
    <div class="modal fade" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="quantityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quantityModalLabel">Tambahkan Kuantitas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="quantityForm">
                        @csrf
                        <div class="form-group">
                            <label for="quantity">Jumlah:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <input type="hidden" id="id_menu" name="id_menu">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="addToCartBtn">Tambahkan ke Keranjang</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            function showCartModal() {
                $.ajax({
                    type: "get",
                    url: "{{ url('api/v2/cart') }}",
                    dataType: "JSON",
                    success: function(response) {
                        let cartItemsHtml = '';

                        $.each(response.data, function(index, item) {
                            console.log('cart here', response)
                            cartItemsHtml += `
                            <div>${item.menu.nama_menu} - Jumlah: ${item.quantity}</div>
                        `;
                        });

                        $('#cartItems').html(cartItemsHtml);
                        $('#cartModal').modal('show');
                    }
                });
            }

            $('i.fa-cart-shopping').click(function() {
                showCartModal();
            })

            function addToCart(id_menu, quantity) {
                $.ajax({
                    type: "POST",
                    url: "{{ url('api/v2/cart/create') }}",
                    dataType: "json",
                    data: {
                        id_menu: id_menu,
                        quantity: quantity
                    },
                    success: function(response) {
                        console.log(response)
                        if (response.message === 'success add cart') {
                            alert('Item telah ditambahkan ke keranjang.');
                            $('#quantityModal').modal('hide');
                            updateCartCount();
                        } else {
                            alert('Gagal menambahkan item ke keranjang.');
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menambahkan item ke keranjang.');
                    }
                });
            }

            $('#addToCartBtn').click(function() {
                const id_menu = $('#id_menu').val();
                const quantity = $('#quantity').val();

                if (quantity <= 0) {
                    alert('Jumlah kuantitas harus lebih besar dari 0.');
                    return;
                }
                addToCart(id_menu, quantity);
            });
        });

        function updateCartCount() {
            $.ajax({
                type: "get",
                url: "{{ url('api/v2/cart/count') }}",
                dataType: "JSON",
                success: function(response) {
                    console.log(response)
                    const cartCount = response.total_items;
                    $('#cartCount').text(cartCount);
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }
        
        $(document).ready(function() {
            updateCartCount();
        });


        $(document).ready(function() {
            $.ajax({
                type: "get",
                url: "{{ url('api/v1/menu') }}",
                dataType: "JSON",
                success: function(response) {
                    let menuList = $('#menu-list');
                    $.each(response.data, function(index, item) {
                        console.log(response)
                        let cardHtml = `  
                            <div class="col-md-4" >
                            <div class="card" style="width: 18rem;">
                                <img src="/uploads/menu/${item.gambar_menu}" class="card-img-top" alt="${item.nama_menu}">
                                <div class="card-body">
                                    <h5 class="card-title">${item.nama_menu}</h5>
                                    <p class="card-text">Harga: ${item.harga}</p>
                                    <button type='button' class="btn btn-primary" data-id-menu="${item.id}" data-toggle="modal" data-target="#quantityModal" onclick="testModal(${item.id})">Tambahkan ke keranjang</button>
                                </div>
                            </div>
                        </div>
                    `;
                        menuList.append(cardHtml);
                    });
                }
            });



            $('#formTambah').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: "post",
                    url: "{{ url('api/v1/menu/create') }}",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.message === 'check your validation') {
                            console.log(response)
                            let error = response.errors;
                            let errorMessage = '';

                            alert('ada data yang kosong')
                        } else {
                            alert('success tambah menu')
                            window.location.reload();
                        }
                    },
                    error: function(error) {
                        console.log('Error', error);
                    }
                });
            })
        });

        function testModal(id_menu) {
            console.log("Button clicked!");
            $('#id_menu').val(id_menu);
            $('#quantityModal').modal('show');
        }
    </script>
</body>

</html>
