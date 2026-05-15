<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index ()
    {
        $categories=Category::get();

        return response()->json(
            [
                "success"=>true,
                "categories"=>CategoryResource::collection($categories),
        ],200);
    }

    public function show(Category $category)
    {
        if(!$category )
            {
                   return response()->json(
            [
                "success"=>false,
                "message"=>"category not found",
        ],404);
            }

           return response()->json(
            [
                "success"=>true,
                "category"=>new CategoryResource($category),
        ],200);
    }

    public function create(CreateCategoryRequest $request)
    {
        $request->validated();

       $category= Category::create([
            "title"=>$request->title,
        ]);

           return response()->json(
            [
                "success"=>true,
                "message"=>"category created successfully",
                "category"=> new CategoryResource($category),
        ],201);
        }
        public function update(CategoryUpdateRequest $request,Category $category)
        {
            $validate=$request->validated();
            $category->update($validate);
            // dd($validate);
            return response()->json(
             [
                 "success"=>true,
                 "message"=>"category updated successfully",
                 "category"=> new CategoryResource($category),
         ],200);

         }
         public function delete(Category $category)
         {
             $category->delete();

             return response()->json(
              [
                  "success"=>true,
                  "message"=>"category deleted successfully",
          ],200);
    }
}
