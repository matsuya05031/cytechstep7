@extends('layouts.app')

@section('content')
<table border="1">
    <tr>
        <th>ID</th>
        <th>商品名</th>
        <th>価格</th>
        <th>在庫</th>
        <th>メーカー</th>
    </tr>
    @foreach($products as $product)
    <tr>
        <td>{{ $product->id }}</td>
        <td>{{ $product->product_name }}</td>
        <td>{{ $product->price }}</td>
        <td>{{ $product->stock }}</td>
        <td>{{ $product->company->company_name }}</td>
    </tr>
    @endforeach
</table>

