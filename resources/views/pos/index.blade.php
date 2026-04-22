@extends('layouts.app')

@section('title', '/ POS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Select Medicine</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="medicines-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Cart</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Customer:</strong> {{ $user->name ?? 'N/A' }} <br>
                        <strong>Pharmacy:</strong> {{ $pharmacy->pharmacy_name ?? 'N/A' }} <br>
                        <strong>Address:</strong> {{ $address->street_name ?? 'N/A' }}, {{ $address->building_number ?? '' }}
                    </div>
                    <form id="order-form">
                        <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                        <input type="hidden" name="pharmacy_id" value="{{ $pharmacy->id ?? '' }}">
                        <input type="hidden" name="delivering_address_id" value="{{ $address->id ?? '' }}">

                        <table class="table table-sm" id="cart-table">
                            <thead>
                                <tr>
                                    <th>Medicine</th>
                                    <th>Price</th>
                                    <th style="width: 100px;">Qty</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Cart items will be added here -->
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between">
                                <strong>Subtotal:</strong>
                                <span id="subtotal">0.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <strong>Discount (¢):</strong>
                                <input type="number" name="discount" id="discount" class="form-control form-control-sm w-25" value="0">
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5>Total:</h5>
                                <h5 id="total">0.00</h5>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-block mt-3" id="confirm-order">Confirm Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function() {
    let cart = [];

    const table = $('#medicines-table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'frtip',
        ajax: '{{ route("pos.medicines") }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'type', name: 'type' },
            { data: 'quantity', name: 'quantity' },
            { data: 'price', name: 'price', render: function(data) { return data + ' ¢'; } },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#medicines-table').on('click', '.add-to-cart', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const price = parseFloat($(this).data('price'));

        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        renderCart();
    });

    function renderCart() {
        const tbody = $('#cart-table tbody');
        tbody.empty();
        let subtotal = 0;

        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            tbody.append(`
                <tr>
                    <td>${item.name}</td>
                    <td>${item.price} ¢</td>
                    <td>
                        <input type="number" class="form-control form-control-sm update-qty" data-index="${index}" value="${item.quantity}" min="1">
                    </td>
                    <td>${itemTotal} ¢</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}">&times;</button>
                    </td>
                </tr>
            `);
        });

        $('#subtotal').text((subtotal / 100).toFixed(2) + ' $');
        updateTotal();
    }

    function updateTotal() {
        let subtotal = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
        let discount = parseFloat($('#discount').val()) || 0;
        let total = (subtotal - discount) / 100;
        $('#total').text(total.toFixed(2) + ' $');
    }

    $('#cart-table').on('change', '.update-qty', function() {
        const index = $(this).data('index');
        const qty = parseInt($(this).val());
        if (qty > 0) {
            cart[index].quantity = qty;
            renderCart();
        }
    });

    $('#cart-table').on('click', '.remove-item', function() {
        const index = $(this).data('index');
        cart.splice(index, 1);
        renderCart();
    });

    $('#discount').on('input', function() {
        updateTotal();
    });

    $('#order-form').on('submit', function(e) {
        e.preventDefault();
        if (cart.length === 0) {
            alert('Cart is empty!');
            return;
        }

        const formData = {
            user_id: $('input[name="user_id"]').val(),
            pharmacy_id: $('input[name="pharmacy_id"]').val(),
            delivering_address_id: $('input[name="delivering_address_id"]').val(),
            items: cart,
            discount: $('#discount').val(),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("pos.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert('Order confirmed successfully! Order ID: ' + response.order_id);
                    cart = [];
                    renderCart();
                    $('#discount').val(0);
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                alert('Error creating order: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endsection
