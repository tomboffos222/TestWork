<?php

namespace App\Http\Controllers\Api\Product;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index(Request $request)
    {
        $products =  Product::withTrashed()->get();
        if ($request['category_id']) {
            $categoryId = $request['category_id'];
            $products = Product::withTrashed()->whereHas('categories',function ($q) use ($categoryId) {
                $q->where('category_id',$categoryId);
            })->get();
        }
        if($request['search'])
            $products = $products->where('name','LIKE','%'.$request['search'].'%');

        if ($request['min_price'] && $request['max_price']) {
            if ($request['min_price'] >= $request['max_price'])
                return response()->json(['msg'=>'Min Price can not be larger than max price'],400);
            $products = $products->whereBetween('price', [$request['min_price'], $request['max_price']]);
        }


        if ($request['isNotDeleted'])
            $products = $products->where('deleted_at',null);

        if ($request['is_publicated'])
            $products = $products->where('is_publicated',1);

        if ($products->isNotEmpty()) {
            foreach ($products as $product){
                $product->categories;
            }
        }

        return response()->json([
            'product' => $products,
        ],200);
    }


    public function store(Request $request){
        $validate = $request->validate([
            'name' => 'required',
            'price'=> 'required|numeric',
            'category_ids.*'=> 'required',
        ]);
        if (count($validate['category_ids']) <2)
            return response()->json(['msg'=>'Category must be 2 or more'],400);
        foreach ($validate['category_ids'] as $category_id){
            $category = Category::find($category_id);
            if (!$category)
                return response()->json([
                    'msg'=> 'Category not found'
                ],400);
        }
        $is_publicated = $request->has('is_publicated');

        $product = Product::create([
           'name'=>$validate['name'],
           'price' => $validate['price'],
           'is_publicated' => $is_publicated,
        ]);

        foreach ($request['category_ids'] as $category){
            ProductCategory::create([
               'product_id' => $product->id,
               'category_id'=> $category
            ]);
        }

        return response()->json(['msg' => 'Product created'],201);

    }

    public function Update(Request $request,$id)
    {
        $validate = $request->validate([
            'price' => 'numeric',

        ]);
        $product = Product::find($id);

        if (!$product)
            return response()->json(['msg' => 'Not found'] , 404);

        if ($request['price'])
            $product['price'] = $request['price'];
        if ($request['name'])
            $product['name'] = $request['name'];
        if ($request['is_publicated'])
            $product['name'] = $request['is_publicated'];

        $product->save();

        if ($request['category_ids']){
            $productRelations = ProductCategory::where('product_id',$product['id'])->get();
            foreach ($productRelations as $productRelation){
                $productRelation->delete();
            }
            foreach ($request['category_ids'] as $category){
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_id'=> $category
                ]);
            }
        }

        return response()->json(['msg' => 'Edited'],200);
    }

    public function Destroy($id){
        $product = Product::find($id);
        if (!$product)
            return response()->json(['msg' => 'Not found'],404);
        $productRelations = ProductCategory::where('product_id',$product['id'])->get();
        foreach ($productRelations as $productRelation){
            $productRelation->delete();
        }

        $product->delete();
        return response()->json(['msg' => 'Deleted'],200);

    }
}
