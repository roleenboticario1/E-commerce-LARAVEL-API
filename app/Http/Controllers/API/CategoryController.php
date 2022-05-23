<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{   
    public function index()
    {
       $category = Category::all();

       return response()->json([
           'status' => 200,
           'category'=>$category
       ]);
    }

    public function allCategery()
    {
       $category = Category::where('status',0)->get();

       return response()->json([
           'status' => 200,
           'category'=>$category
       ]); 
    }

    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|max:191',
            'name' => 'required|max:191',
            'slug' => 'required|max:191',
       ]);

       if($validator->fails())
       {
           return response()->json([
               'status' => 400,
               'validation_errors'=>$validator->messages()
           ]);
       }
       else
       {
            $category = new Category;
            $category->meta_title = $request->meta_title;
            $category->meta_keyword = $request->meta_keyword;
            $category->meta_description = $request->meta_description;
            $category->slug = $request->slug;
            $category->name = $request->name;
            $category->description = $request->description;
            $category->status = $request->status == true ? '1' : '0';
            $category->save();

            return response()->json([
               'status'=> 200,
               'message' => 'Added Category Successfully!'
            ]);
       } 
    }

    public function edit($id)
    {
       $category = Category::find($id);
       
       if($category)
       {
            return response()->json([
               'status' => 200,
               'category'=>$category
           ]);
       }
       else
       {
           return response()->json([
               'status' => 404,
               'message'=> "No Categoty ID Found!"
           ]);
       }

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|max:191',
            'name' => 'required|max:191',
            'slug' => 'required|max:191',
       ]);

       if($validator->fails())
       {
           return response()->json([
               'status' => 422,
               'validation_errors'=>$validator->messages()
           ]);
       }
       else
       {
            $category = Category::find($id);

            if($category){
                $category->meta_title = $request->meta_title;
                $category->meta_keyword = $request->meta_keyword;
                $category->meta_description = $request->meta_description;
                $category->slug = $request->slug;
                $category->name = $request->name;
                $category->description = $request->description;
                $category->status = $request->status == true ? '1' : '0';
                $category->save();

                return response()->json([
                   'status'=> 200,
                   'message' => 'Updated Category Successfully!'
                ]);
            }
            else
            {
                return response()->json([
                   'status' => 404,
                   'message'=> "No Categoty ID Found!"
                ]);
            }

       } 
    }

    public function destroy($id)
    {
        $category = category::find($id);

       if($category)
       {    
            $category->delete();
            return response()->json([
               'status' => 200,
               'message'=> 'Deleted Category Successfully!'
           ]);
       }
       else
       {
           return response()->json([
               'status' => 404,
               'message'=> "No Categoty ID Found!"
           ]);
       }

    }

}
