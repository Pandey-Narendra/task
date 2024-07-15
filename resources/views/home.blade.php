@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-black">Products</h1>
        <div class="product-items">
            
            @foreach ($products as $product)
                <div class="card mb-3">
                    <div class="row no-gutters">
                        
                        <div class="col-md-4">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img img-fluid" alt="{{ $product->name }}">
                        </div>
                        
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">${{ number_format($product->price, 2) }}</p>
                                <p class="card-text">{{ $product->description }}</p>
                                <p class="card-text">Quantity: {{ $product->quantity }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="pagination">
            {{ $products->links() }}
        </div>
    </div>
@endsection