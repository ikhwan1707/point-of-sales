<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products; //untuk memanggil model products
use App\Categories; //untuk memanggil model categories

class ProductsController extends Controller
{

    public function index()
    {
        $products = Products::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Categories::all();
        return view('products.create', compact('categories'));
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
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'      => 'required|in:available,unavailable',
            'categorie_id' => 'required',
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('products', 'public');
        }

        Products::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'image'       => $image,
            'status'      => $request->status,
            'categorie_id' => $request->categorie_id,
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Products::find($id);
        $categories = Categories::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'       => 'required|in:available,unavailable',
            'categorie_id' => 'required|exists:categories,categorie_id',
        ]);

        $product = Products::findOrFail($id);

        // default pakai gambar lama
        $image = $product->image;

        // kalau ada upload baru, hapus lama lalu simpan baru
        if ($request->hasFile('image')) {
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $image = $request->file('image')->store('products', 'public');
        }

        // update manual per field
        $product->update([
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'stock'        => $request->stock,
            'image'        => $image,
            'status'       => $request->status,
            'categorie_id' => $request->categorie_id,
        ]);

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Products::where('product_id', $id)->delete();
        return redirect()->route('products.index');
    }
}