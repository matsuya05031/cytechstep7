<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>商品名</th>
            <th>価格</th>
            <th>在庫</th>
            <th>メーカー</th>
            <th>画像</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->product_name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            <td>{{ $product->company->company_name }}</td>
            <td>
                @if ($product->img_path)
                    <img src="{{ asset($product->img_path) }}" alt="商品画像" width="80">
                @else
                    画像なし
                @endif
            </td>
            <td>
                <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">詳細</a>

                <form method="POST" action="{{ route('products.destroy', $product) }}"
                      style="display:inline;" onsubmit="return confirm('本当に削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">削除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


