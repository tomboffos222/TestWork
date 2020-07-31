<?php

namespace App\Http\Controllers\Api\Category;

use App\Category;
use App\Http\Controllers\Controller;
use App\ProductCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function store(Request $request){
        $validate = $request->validate([
           'title' => 'required|unique:categories'
        ]);

        Category::create([
            'title' =>$request['title']
        ]);

        return response()->json(['msg'=>'Category created'],201);
    }

    public function Destroy($id){
        $category = Category::find($id);
        if(!$category)
            return response()->json(['msg'=> 'Not found'],404);
        $categoryRelations = ProductCategory::where('category_id',$category['id'])->get();
        if($categoryRelations->isNotEmpty()){
            return response()->json(['msg'=>'Can not delete there are have products in this category'],400);
        }else{
            $category->delete();
            return response()->json(['msg'=> 'Deleted'],200);
        }



    }
}
