@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品情報一覧</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">商品新規登録</a>

   

    <div class="products mt-5">
        <h2>商品情報</h2>
        <form id="search-form">
            <input type="text" name="product_name" placeholder="商品名">
            <input type="number" name="price_min" placeholder="価格下限">
            <input type="number" name="price_max" placeholder="価格上限">
            <input type="number" name="stock_min" placeholder="在庫下限">
            <input type="number" name="stock_max" placeholder="在庫上限">

            <select name="company_id">
                <option value="">-- 企業名で絞り込み --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>

            <button type="submit">検索</button>
        </form>

        <!-- 検索結果の表示 -->
        <div id="product-list">
            @include('products.partials.product_table', ['products' => $products])
        </div>
  
        {{-- jQueryの処理 --}}
        <script>
        $(document).ready(function () {
            $('#search-form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('products.search') }}",
                    type: "GET",
                    data: $(this).serialize(),
                    success: function (data) {
                        $('#product-list').html(data);
                    },
                    error: function () {
                        alert('検索に失敗しました');
                    }
                });
            });
        });
        </script>
    </div>

    
</div>
@endsection