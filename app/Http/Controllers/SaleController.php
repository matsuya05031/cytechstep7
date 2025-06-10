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
            'company_id' => 'required|exists:companies,id',
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        DB::beginTransaction();

        try {
            $product = Product::lockForUpdate()->find($productId);

            if ($product->stock < $quantity) {
                DB::rollBack();
                return response()->json(['message' => '在庫が不足しています'], 400);
            }

            $product->stock -= $quantity;
            $product->save();

            Sale::create([           
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'company_id' => $request->company_id,
            ]);

            DB::commit();

            return response()->json(['message' => '購入が完了しました'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => '購入処理中にエラーが発生しました'], 500);
        }
    }
}
