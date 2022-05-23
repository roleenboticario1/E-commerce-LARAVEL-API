<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use File;

class ProductController extends Controller
{   

    public function index()
    {
       $product = Product::all();

       return response()->json([
           'status' => 200,
           'product'=>$product
       ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'=> 'required|max:191',
            'meta_title' => 'required|max:191',
            'name' => 'required|max:191',
            'slug' => 'required|max:191',
            'brand' => 'required|max:191',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
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
            $product = new Product;
            $product->meta_title = $request->meta_title;
            $product->meta_keyword = $request->meta_keyword;
            $product->meta_description = $request->meta_description;
            
            $product->category_id = $request->category_id;
            $product->slug = $request->slug;
            $product->name = $request->name;
            $product->description = $request->description;

            $product->brand = $request->brand;
            $product->selling_price = $request->selling_price;
            $product->original_price = $request->original_price;
            $product->qty = $request->qty;

            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalName();
                $filename = time() ." . ". $extension;
                $file->move('upload/product/', $filename);
                $product->image = 'upload/product/'.$filename;
            }
            
            $product->featured = $request->featured == true ? '1' : '0';
            $product->popular = $request->popular == true ? '1' : '0';
            $product->status = $request->status == true ? '1' : '0';;
            $product->save();

            return response()->json([
               'status'=> 200,
               'message' => 'Added Product Successfully!'
            ]);
       } 
    }

    public function edit($id)
    {
       $product = Product::find($id);
       
       if($product)
       {
            return response()->json([
               'status' => 200,
               'product'=>$product
           ]);
       }
       else
       {
           return response()->json([
               'status' => 404,
               'message'=> "No Product ID Found!"
           ]);
       }

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id'=> 'required|max:191',
            'meta_title' => 'required|max:191',
            'name' => 'required|max:191',
            'slug' => 'required|max:191',
            'brand' => 'required|max:191',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required'
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
            $product = Product::find($id);
            
            if($product){

                $product->meta_title = $request->meta_title;
                $product->meta_keyword = $request->meta_keyword;
                $product->meta_description = $request->meta_description;
                
                $product->category_id = $request->category_id;
                $product->slug = $request->slug;
                $product->name = $request->name;
                $product->description = $request->description;

                $product->brand = $request->brand;
                $product->selling_price = $request->selling_price;
                $product->original_price = $request->original_price;
                $product->qty = $request->qty;
                
                //If you dont update it will ignore this code
                if($request->hasFile('image'))
                {   
                    $path = $product->image;
                    if(File::exists($product))
                    {
                        File::delete(); //Delete Existing Image
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalName();
                    $filename = time() ." . ". $extension;
                    $file->move('upload/product/', $filename);
                    $product->image = 'upload/product/'.$filename;
                }
                
                $product->featured = $request->featured;
                $product->popular = $request->popular;
                $product->status = $request->status;
                $product->update();

                return response()->json([
                   'status'=> 200,
                   'message' => 'Updated Product Successfully!'
                ]);  
            }
            else
            {
                return response()->json([
                   'status' => 404,
                   'message'=> "No Product ID Found!"
                ]); 
            } 
       } 
    }


}
