@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-3">
        
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top img-fluid" style="height: 300px; object-fit: contain;" alt="{{ $product->name }}">
        @endif
       
        <div class="card-body">
            <h5 class="card-title">{{ $product->name }}</h5>
            <p class="card-text">${{ number_format($product->price, 2) }}</p>
            <p class="card-text">{{ $product->description }}</p>
            <p class="card-text">Quantity: {{ $product->quantity }}</p>
           
            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
               
                <div>
                    @if($product->user_id == Auth::id())
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
