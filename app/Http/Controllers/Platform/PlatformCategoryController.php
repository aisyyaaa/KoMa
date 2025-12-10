<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class PlatformCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('platform.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('platform.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255|unique:categories,name']);
        Category::create($data);
        return redirect()->route('platform.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return view('platform.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('platform.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);
        $category->update($data);
        return redirect()->route('platform.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Add logic to check if category is in use before deleting
        if ($category->products()->exists()) {
            return redirect()->route('platform.categories.index')->withErrors(['delete' => 'Cannot delete category that is in use.']);
        }
        $category->delete();
        return redirect()->route('platform.categories.index')->with('success', 'Category deleted successfully.');
    }
}
