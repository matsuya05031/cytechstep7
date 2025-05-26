<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['message' => '在庫が不足しています'], 400);
        }

        DB::biginTransaction(function () use ($product, $request) {
            Sale::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);

            $product->decrement('stock', $request->quantity);
        });

        return response()->json(['message' => '購入が完了しました'], 200);
    }
}
