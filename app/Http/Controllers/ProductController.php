<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
        ]);

        try {
            $product = new Product([
                'product_name' => $request->get('product_name'),
                'company_id' => $request->get('company_id'),
                'price' => $request->get('price'),
                'stock' => $request->get('stock'),
                'comment' => $request->get('comment'),
            ]);
            
            if($request->hasFile('img_path')){
                $filename = $request->img_path->getClientOriginalName();
                $filePath = $request->img_path->storeAs('products',$filename,'public');
                $product->img_path = '/storage/' . $filePath;
            }
            
            $product->save();
            return redirect()->route('products.create')->with('status', '商品を登録しました！');
        
        } catch (\Exception $e) {
            return back()->withErrors('登録中にエラーがはっせいしました：' . $e->getMessage());
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
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image|max:2048',
        ]);

        try {
            if ($request->hasFile('img_path')) {
                if ($product->img_path && File::exists(public_path($product->img_path))) {
                    File::delete(public_path($product->img_path));
                }
                
                $path = $request->file('img_path')->store('images', 'public');
                $product->img_path = 'storage/' . $path;
            }
            
            $product->product_name = $request->product_name;
            $product->company_id = $request->company_id;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->comment = $request->comment;
            
            $product->save();
            
            return redirect()->route('products.index')->with('success', '商品情報を更新しました！');

        } catch (\Exception $e) {
            return back()->withErrors('更新中にエラーが発生しました :' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', '商品を削除しました');
        } catch (\Exception $e) {
            return back()->withErrors('削除中にエラーが発生しました :' . $e->getMessage());
        }
    }
}
