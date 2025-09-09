<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // GET /products (admin)
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    // POST /products (admin)
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|max:150',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // Upload gambar kalau ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'image'       => $imagePath,
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'data'    => $product
        ], 201);
    }

    // GET /products/{id}
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    // PUT /products/{id}
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|max:150',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // Kalau ada file baru, hapus yang lama
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update($request->only(['category_id', 'name', 'description', 'price', 'stock', 'image']));

        return response()->json([
            'message' => 'Produk berhasil diupdate',
            'data'    => $product
        ]);
    }

    // DELETE /products/{id}
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar juga
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus'
        ]);
    }

    // GET /catalog (customer)
    public function catalog(Request $request)
    {
        $query = Product::with('category');

        // filter kategori
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // search produk
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $products = $query->paginate(10);

        return response()->json($products);
    }
}
