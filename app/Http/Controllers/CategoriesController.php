<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categories;

class CategoriesController extends Controller
{

    public function index()
    {
        $categories = Categories::all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Categories::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('category.index');
    }
    public function edit($id)
    {
        $dataeditcategory = Categories::find($id);
        return view('categories.edit', compact('dataeditcategory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id . ',categorie_id',
            'description' => 'nullable|string',
        ]);

        $updatedata = Categories::findOrFail($id);
        
        $updatedata->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('category.index');
    }

    public function destroy($id)
    {
        Categories::where('categorie_id', $id)->delete();
        return redirect()->route('category.index');
    }
}