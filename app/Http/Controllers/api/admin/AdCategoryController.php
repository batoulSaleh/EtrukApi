<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class AdCategoryController extends Controller
{
    public function index(){
        $categories=Category::all();
        $response = [
            'message'=>'All Categories',
            'Categories' => $categories
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

    public function store(Request $request){
        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
            'description_en' => 'string|max:500',
            'description_ar' => 'string|max:500',
            'image' => 'image|max:2048',
        ]);

        $image_path = $request->file('image')->store('api/categories','public');

        $category = Category::create([
            'name_en' => $request->name_en,
            'name_ar'=> $request->name_ar,
            'description_en'=> $request->description_en,
            'description_ar'=> $request->description_ar,
            'image' => asset('storage/'.$image_path),
        ]);

        $response = [
            'message'=>'Category created successfully',
            'Category' => $category
        ];
        return response($response,201);
    }

    public function update(Request $request,$id){
        $category=Category::find($id);

        $request->validate([
            'name_en' => 'required|string|max:200',
            'name_ar' => 'required|string|max:200',
            'description_en' => 'string|max:500',
            'description_ar' => 'string|max:500',
            'image' => 'image|max:2048',
        ]);

        $category->update([
                    'name_en' => $request->name_en,
                    'name_ar'=> $request->name_ar,
                    'description_en'=> $request->description_en,
                    'description_ar'=> $request->description_ar,
                ]);
        if($request->file('image')){
            $image_path = $request->file('image')->store('api/categories','public');
            $category->image = asset('storage/'.$image_path);
            $category->save();
        }
        

        $response = [
            'message'=>'Category updated successfully',
            'Category' => $category
        ];
        return response($response,201);
    }

    public function destroy($id){
        $category = Category::findOrFail($id);
        $category->delete();
        $response = [
            'message'=>'Category deleted successfully',
        ];
        return response($response,201);
    }
}
