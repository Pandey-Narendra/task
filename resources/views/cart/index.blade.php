@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Cart</h1>

    @if (session('success_message'))
        <div class="alert alert-success">
            {{ session('success_message') }}
        </div>
    @elseif (session('error_message'))
        <div class="alert alert-danger">
            {{ session('error_message') }}
        </div>
    @endif

    <div id="message-container"></div>

    <div class="cart-items">
        
        @foreach ($cartItems as $item)
            <div class="card mb-3" data-product-id="{{ $item->product_id }}">
                <div class="row no-gutters">
                    
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="card-img img-fluid" alt="{{ $item->product->name }}">
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->product->name }}</h5>
                            <p class="card-text">Price: $<span class="item-price">{{ number_format($item->product->price, 2) }}</span></p>
                            
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary decrease-quantity" type="button" data-cart-id="{{ $item->id }}">-</button>
                                </div>
                                <input type="text" class="form-control quantity" value="{{ $item->quantity }}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary increase-quantity" type="button" data-cart-id="{{ $item->id }}">+</button>
                                </div>
                            </div>
                            
                            <p class="card-text">Total: $<span class="item-total">{{ number_format($item->product->price * $item->quantity, 2) }}</span></p>
                            
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="pagination justify-content-center">
        {{ $cartItems->links() }}      
    </div>
    
    <h3>Grand Total: $<span id="grand-total">{{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}</span></h3>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    function updateGrandTotal() {
        let grandTotal = 0;
        $('.item-total').each(function() {
            grandTotal += parseFloat($(this).text());
        });
        $('#grand-total').text(grandTotal.toFixed(2));
    }

    function showMessage(message, type) {
        // console.log("cart.index", "showMessage");
        const messageContainer = $('#message-container');
        messageContainer.html(`<div class="alert alert-${type}" role="alert">${message}</div>`);
        setTimeout(() => {
            messageContainer.html('');
        }, 5000); 
    }

    $('.increase-quantity').click(function() {
        // console.log("cart.index", ".increase-quantity");
        const cartItemId = $(this).data('cart-id');
        const quantityInput = $(this).closest('.card').find('.quantity');
        let quantity = parseInt(quantityInput.val()) + 1;
        const price = parseFloat($(this).closest('.card-body').find('.item-price').text());

        $.ajax({
            
            url: '/cart/' + cartItemId,
           
            method: 'PUT',
           
            data: {
                _token: '{{ csrf_token() }}',
                quantity: quantity
            },
            
            success: function() {
                quantityInput.val(quantity);
                const itemTotal = price * quantity;
                // console.log("cart.index", ".increase-quantity", "success");
                quantityInput.closest('.card-body').find('.item-total').text(itemTotal.toFixed(2));
                updateGrandTotal();
                showMessage('Quantity increased successfully', 'success');
            },
            
            error: function(xhr, status, error) {
                // console.log("cart.index", ".increase-quantity", "error");
                // console.error(xhr.responseText);
                showMessage('Error increasing quantity', 'danger');
            }
        });
    });

    $('.decrease-quantity').click(function() {
        // console.log("cart.index", ".decrease-quantity");
        const cartItemId = $(this).data('cart-id');
        const quantityInput = $(this).closest('.card').find('.quantity');
        let quantity = parseInt(quantityInput.val());
        const price = parseFloat($(this).closest('.card-body').find('.item-price').text());

        if (quantity > 1) {
            quantity -= 1;

            $.ajax({
                url: '/cart/' + cartItemId,
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: quantity
                },
                success: function() {
                    quantityInput.val(quantity);
                    const itemTotal = price * quantity;
                    // console.log("cart.index", ".decrease-quantity", "success");
                    quantityInput.closest('.card-body').find('.item-total').text(itemTotal.toFixed(2));
                    updateGrandTotal();
                    showMessage('Quantity decreased successfully', 'success');
                },
                error: function(xhr, status, error) {
                     // console.log("cart.index", ".decrease-quantity", "error");
                    // console.error(xhr.responseText);
                    showMessage('Error decreasing quantity', 'danger');
                }
            });
        }
    });
});
</script>
@endsection

