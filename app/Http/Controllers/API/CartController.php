<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
       if(auth('sanctum')->check()){ 
          
          $user_id = auth('sanctum')->user()->id;
          $product_id = $request->product_id;
          $product_qty = $request->product_qty;

          $productCheck = Product::where('id', $product_id)->first();

          if(!$productCheck){
              return response()->json([
                   'status'=> 404,
                   'message' => 'Product Not Found'
               ]); 
          }

          if(Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists())
          {
                return response()->json([
                   'status'=> 409,
                   'message' => $productCheck->name.'Already Added to Cart'
               ]);
          }

          $cartItem = new Cart;
          $cartItem->user_id = $user_id;
          $cartItem->product_id = $product_id;
          $cartItem->product_qty = $product_qty;
          $cartItem->save();

          return response()->json([
                'status'=> 201,
                'message' => 'Successfully Added to cart!'
          ]);  
       }
       else
       {
           return response()->json([
               'status'=> 401,
               'message' => 'Please, Log in First to Add to Cart'
           ]);  
       }
    }

    public function cart()
    {
      
       if(auth('sanctum')->check())
       { 
          $user_id = auth('sanctum')->user()->id;
          $cartItems = Cart::where('user_id', $user_id)->get();

           return response()->json([
               'status'=> 200,
               'cart' => $cartItems
           ]);  
       }
       else
       {
           return response()->json([
               'status'=> 401,
               'message' => 'Please, Log in to View Cart Data'
           ]);  
       }
    }
}
