@extends("layouts/app")
@section("content")


    <div class="row">
        <div>
            <a href="{{ url('products/create') }}">
                <button>添加产品</button>
            </a>
            @foreach($products as $article)
                @include('shared/_product')
            @endforeach

        </div>
        {{ $products->links() }}
    </div>

@endsection