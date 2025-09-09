<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // git categoris
        $categories =Categories::all();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // post categoris
        $request->validate([
        'name' => 'required|unique:categories,name|max:100',
       ]);


        $category = Categories::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get categoris by id
        $category = Categories::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // put categoris by id
        $category = Categories::findOrFail($id);

        $request->validate([
        'name' => 'required|max:100|unique:categories,name,' . $id
        ]);


        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diupdate',
            'data' => $category
        ]);
    }

    /*
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete categoris by id
        $category = Categories::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
