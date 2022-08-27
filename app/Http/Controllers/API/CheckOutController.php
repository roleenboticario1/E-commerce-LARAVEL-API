<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;

class CheckOutController extends Controller
{
    public function place_order(Request $request)
    {
       if(auth('sanctum')->check())
       { 
          $validator = Validator::make($request->all(), [
              'firstname' => 'required|max:191',
              'lastname' => 'required|max:191',
              'phone' => 'required|max:191',
              'email_address' => 'required|max:191',
              'address' => 'required|max:191',
              'city' => 'required|max:191',
              'state' => 'required|max:191',
              'zip_code' => 'required|max:191',
          ]);

          if($validator->fails())
          {   
              return response()->json([
                'status'=> 422,
                'errors' => $validator->messages(),
              ]); 
          }
          else
          {   
              $user_id = auth('sanctum')->user()->id;

              $order = new Order;
              $order->user_id = $user_id;
              $order->firstname = $request->firstname;
              $order->lastname = $request->lastname;
              $order->phone = $request->phone;
              $order->email_address = $request->email_address;
              $order->address = $request->address;
              $order->city = $request->city;
              $order->state = $request->state;
              $order->zip_code = $request->zip_code;

              $order->payment_mode = 'COD';
              $order->tracking_no = 'rolZiNe'.rand(1111,9999);
              $order->save();

              $cart = Cart::where('user_id', $user_id)->get();

              $orderitems = [];

              foreach($cart as $item){
                 $orderitems[] = [
                   'product_id' => $item->product_id,
                   'qty' => $item->product_qty,
                   'price' => $item->products->selling_price,
                 ];
                 
                 $item->products->update([
                              //products table qty - carts table qty
                    'qty' => $item->products->qty  - $item->product_qty
                 ]);
              }

               //This orderitems relationship between Order and OrderItems
              $order->orderitems()->createMany($orderitems);

              //after inserting data from cart we should delete.para yung current order sa cart matapos na
              Cart::destroy($cart);

              return response()->json([
                'status'=> 200,
                'message' => "Order Placed Successfully",
              ]);
          }
     
       }
       else
       {
           return response()->json([
               'status'=> 401,
               'message' => 'Please, Log in to Continue'
           ]);  
       }
    }
}
