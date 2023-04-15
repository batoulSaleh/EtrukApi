<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class UsCategoryController extends Controller
{
    public function index(){
        $categories = Category::select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'image',
            )->get();
        $response = [
            'message'=>'All Categories',
            'Categories' => $categories
        ];
        return response($response,201);
    }

    public function show($id)
    {
        $category = Category::select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'image',

            )->where('id',$id)->first();        
            $response = [
            'message'=>'specific Category with id',
            'Category' => $category
        ];
        return response($response,201);
    }
}
