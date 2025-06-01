@php
    $currentSortColumn = request('sort_column', 'id');
    $currentSortOrder = request('sort_order', 'desc');
@endphp

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            @php
                function sortLink($label, $column, $currentSortColumn, $currentSortOrder) {
                    $nextOrder = ($currentSortColumn === $column && $currentSortOrder === 'asc') ? 'desc' : 'asc';
                    $arrow = '';
                    if ($currentSortColumn === $column) {
                        $arrow = $currentSortOrder === 'asc' ? '↑' : '↓';
                    }
                    return '<a href="#" class="sortable" data-column="' . $column . '" data-order="' . $nextOrder . '">' . $label . ' ' . $arrow . '</a>';
                }
            @endphp
            <th>{!! sortLink('ID', 'id', $currentSortColumn, $currentSortOrder) !!}</th>
            <th>{!! sortLink('商品名', 'product_name', $currentSortColumn, $currentSortOrder) !!}</th>
            <th>{!! sortLink('価格', 'price', $currentSortColumn, $currentSortOrder) !!}</th>
            <th>{!! sortLink('在庫', 'stock', $currentSortColumn, $currentSortOrder) !!}</th>
            <th>{!! sortLink('メーカー', 'company_name', $currentSortColumn, $currentSortOrder) !!}</th>
            <th>画像</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr id="product-row-{{ $product->id }}">
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
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">詳細</a>

                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $product->id }}">削除</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


