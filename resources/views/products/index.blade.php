@extends('layouts.app')

@section('content')
<div class="container">
    <div id="message-container"></div> 

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>All Products</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
    </div>

    @if (session('success_message'))
        <div class="alert alert-success">
            {{ session('success_message') }}
        </div>
    @elseif (session('error_message'))
        <div class="alert alert-danger">
            {{ session('error_message') }}
        </div>
    @endif

    <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top img-fluid product-image" alt="{{ $product->name }}">
                   
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">${{ number_format($product->price, 2) }}</p>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <button class="btn btn-primary add-to-cart m-1" data-product-id="{{ $product->id }}">Add to Cart</button>
                       
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info m-1">View</a>
                            @if($product->user_id == Auth::id())
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning m-1">Edit</a>
                       
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline m-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination justify-content-center">
    {{ $products->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.add-to-cart').click(function() {
            const productId = $(this).data('product-id');
            // console.log("product.index", productId);
            $.ajax({
                url: '{{ route("cart.store") }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // console.log("product.index", "success");
                    showSuccessMessage(response.success);
                },
                error: function(xhr, status, error) {
                    // console.log("product.index", "error");
                    showErrorMessage(xhr.responseText);
                }
            });
        });

        function showSuccessMessage(message) {
            // console.log("showSuccessMessage", message);
            $('#message-container').html('<div class="alert alert-success">' + message + '</div>');
        }

        function showErrorMessage(message) {
            // console.log("showErrorMessage", "message");
            $('#message-container').html('<div class="alert alert-danger">' + message + '</div>');
        }
    });
</script>
@endsection
