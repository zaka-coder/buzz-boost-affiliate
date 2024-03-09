<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required|unique:categories,name,',
        ]);

        $imageUrl = null;

        // check for image
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);

            // upload image and get path
            $image = $request->file('image');
            $img_ext = strtolower($image->getClientOriginalExtension());
            $filename =  time() . '.' . $img_ext;
            $folderPath = 'images/categories/';
            $image->move($folderPath, $filename); // save image to public folder
            $imageUrl = $folderPath .  $filename; // save image path
        }

        // create slug from name
        $slug = Str::slug($request->name);

        // create category
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $slug,
            'image' => $imageUrl,
            'parent_id' => $request->parent_id,
        ]);

        // redirect
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        $subcategories = $category->subCategories()->get();
        // dd($categories);
        return view('admin.categories.show', compact('subcategories', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        // validation
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
        ]);

        $imageUrl = $category->image;

        // check for image
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);

            // delete old image
            if ($category->image) {
                $oldImagePath = public_path($category->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // upload image and get path
            $image = $request->file('image');
            $img_ext = strtolower($image->getClientOriginalExtension());
            $filename =  time() . '.' . $img_ext;
            $folderPath = 'images/categories/';
            $image->move($folderPath, $filename); // save image to public folder
            $imageUrl = $folderPath .  $filename; // save image path
        }

        // create slug from name
        $slug = Str::slug($request->name);


        // update category
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $slug,
            'image' => $imageUrl,
            'parent_id' => $request->parent_id,
        ]);

        // redirect
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        // check for subcategories
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category with items!');
        }
        
        // check for subcategories
        if ($category->children()->count() > 0) {
            // delete subcategories
            // $category->children()->delete();
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category with subcategories!');
        }

        // check for image and delete it
        if ($category->image) {
            $oldImagePath = public_path($category->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }


        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }
}
