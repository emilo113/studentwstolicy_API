<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        $category = new Category();
        $category->name = $request->input('name');
        $category->save();

        return response()->json([
            'category' => $category
        ], 201);
    }

    public function getCategories(Request $request)
    {
        return response()->json([
            'categories' => Category::all([
                'id', 'name'
            ])
        ]);
    }
}