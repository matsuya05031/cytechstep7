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
            <input type="hidden" name="sort_column" id="sort_column" value="id">
            <input type="hidden" name="sort_order" id="sort_order" value="desc">

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

        <script>
        $(document).ready(function () {
            $(document).on('click', '.delete-btn', function () {
                const productId = $(this).data('id');
                if (!confirm('本当に削除しますか？')) return;
                
                $.ajax({
                    url: '/products/' + productId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}',
                    },
                    success: function () {
                        $('#product-row-' + productId).fadeOut(300, function () {
                            $(this).remove();
                        });
                        alert('商品を削除しました');
                    },
                    error: function () {
                        alert('商品の削除に失敗しました');
                    }
                });
            });
        });
        </script>

        <script>
        $(document).ready(function () {
            $(document).on('click', '.sortable', function () {
                const $header = $(this);
                const column = $header.data('column');
                const currentOrder = $header.data('order') || 'desc';
                const nextOrder = currentOrder === 'asc' ? 'desc' : 'asc';

                 $('#sort_column').val(column);
                 $('#sort_order').val(nextOrder);

                console.log("ソートカラム: ", column, "現順: ", currentOrder, "次順: ", nextOrder);

                $.ajax({
                    url: "{{ route('products.search') }}",
                    type: "GET",
                    data: $('#search-form').serialize(),
                    success: function (data) {
                        $('#product-list').html(data);
                        
                        $(`.sortable[data-column="${column}"]`).data('order', nextOrder);
                    },
                    error: function () {
                        alert('ソートに失敗しました');
                    }
                });
            });
        });
        </script>
    </div>
</div>
@endsection