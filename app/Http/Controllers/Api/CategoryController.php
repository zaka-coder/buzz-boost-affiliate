<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getSubCategories($category_id)
    {
        $subCategories = Category::with('children')->where('parent_id', $category_id)->get();
        return response()->json([
            'data' => $subCategories
        ]);
    }

}
