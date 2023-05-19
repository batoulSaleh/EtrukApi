<?php

namespace App\Http\Controllers\api\charity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class ChCategoryController extends Controller
{
    public function index(){
        $categories=Category::all();
        $response = [
            'message'=>'All Categories',
            'Categories' => $categories,
            'count' => count($categories)
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $category=Category::find($id);
        $response = [
            'message'=>'specific Category with id',
            'Category' => $category
        ];
        return response($response,201);
    }
}
