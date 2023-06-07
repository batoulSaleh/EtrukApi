<?php

namespace App\Http\Controllers\api\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class UsCategoryController extends Controller
{
    public function index(){
        $categories = Category::with('casees')->select(
            'id',
            'name_'.app()->getLocale().' as name',
            'description_'.app()->getLocale().' as description',
            'image',
            )->get();
        $response = [
            'message'=>trans('api.fetch'),
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
                'message'=>trans('api.fetch'),
                'Category' => $category
        ];
        return response($response,201);
    }
}
