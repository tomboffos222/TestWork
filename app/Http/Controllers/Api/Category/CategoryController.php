<?php

namespace App\Http\Controllers\Api\Category;

use App\Category;
use App\Http\Controllers\Controller;
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
}
