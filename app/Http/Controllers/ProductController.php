<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductRequest;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::with('company')->orderBy('id', 'desc')->get();
        $companies = Company::all();

        $query = Product::query();
        if ($request->filled('product_name')) {
           $query->where('product_name', 'like', '%' .$request->product_name . '%');
        }

        if ($request->filled('company_id')) {
           $query->where('company_id', $request->company_id);
        }

        $products = $query->with('company')->get();
        $companies = Company::all();

        return view('products.index', compact('products', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = new Product($request->only([
                'product_name', 'company_id', 'price', 'stock', 'comment'
            ]));
            
            if($request->hasFile('img_path')) {
                $filename = $request->img_path->getClientOriginalName();
                $filePath = $request->img_path->storeAs('products', $filename, 'public');
                $product->img_path = '/storage/' . $filePath;
            }
            
            $product->save();
            return redirect()->route('products.create')->with('status', '商品を登録しました！');
        
        } catch (\Exception $e) {
            return back()->withErrors('登録中にエラーが発生しました：' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load('company');

        return view('products.show', ['product'=> $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            if ($request->hasFile('img_path')) {
                if ($product->img_path && File::exists(public_path($product->img_path))) {
                    File::delete(public_path($product->img_path));
                }
                
                $path = $request->file('img_path')->store('images', 'public');
                $product->img_path = 'storage/' . $path;
            }
            
            $product->fill($request->only([
                'product_name', 'company_id', 'price', 'stock', 'comment'
            ]));
            
            $product->save();
            
            return redirect()->route('products.edit', $product->id)->with('success', '商品情報を更新しました！');

        } catch (\Exception $e) {
            return back()->withErrors('更新中にエラーが発生しました :' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = Product::query()->with('company');

        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('stock_min')) {
            $query->where('stock', '>=', $request->stock_min);
        }

        if ($request->filled('stock_max')) {
            $query->where('stock', '<=', $request->stock_max);
        }

        $sortColumn = $request->input('sort_column', 'id');
        $sortOrder = $request->input('sort_order', 'desc');

        $sortableColumns = ['id', 'product_name', 'price', 'stock'];
        if (in_array($sortColumn, $sortableColumns)) {
            $query->orderBy($sortColumn, $sortOrder);
        }

        $products = $query->get();

        return view('products.partials.product_table', compact('products'))->render();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => '削除しました']);
    }
}