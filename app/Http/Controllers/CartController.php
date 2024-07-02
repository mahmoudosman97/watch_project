<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cart()
    {
       return view('cart');
    }
    

    
    public function addToCart(Request $request)
    {
        if($request->session()->has('cart')){

            $cart = $request->session()->get('cart');
            $products_array_ids = array_column($cart , 'id');

            $id=$request->input('id');

            if(!in_array($id,$products_array_ids)){
                $name = $request->input('name');
                $quantity = $request->input('quantity');
                $price = $request->input('price');
                $sale_price = $request->input('sale_price');
                $image = $request->input('image');

                if ($sale_price !=null) {
                    $price_to_charge = $sale_price;
                }else{
                    $price_to_charge = $price;
                }
            
                $product_array = array(
                    'id'=>$id,
                    'name'=>$name,
                    'quantity'=>$quantity,
                    'image'=>$image,
                    'price'=>$price_to_charge,
                );

                $cart[$id] = $product_array;
                $request->session()->put('cart',$cart);


                 // Product is aleary in the Cart
            }else{
                 echo "<script>alert('Product is aleardy in the cart')</script>" ;
            }

            $this->calculateTotalCart($request);

            return view('cart');

            
        //    if we dont have a cart in the session
        }else{
               $cart = array();

                $id=$request->input('id');
                $name = $request->input('name');
                $quantity = $request->input('quantity');
                $price = $request->input('price');
                $sale_price = $request->input('sale_price');
                $image = $request->input('image');

                if ($sale_price !=null) {
                    $price_to_charge = $sale_price;
                }else{
                    $price_to_charge = $price;
                }
            
                $product_array = array(
                    'id'=>$id,
                    'name'=>$name,
                    'quantity'=>$quantity,
                    'image'=>$image,
                    'price'=>$price_to_charge,
                );

                $cart[$id] = $product_array;
                $request->session()->put('cart',$cart);


                $this->calculateTotalCart($request);

                return view('cart');

        }

            

        
    }

    

    public function calculateTotalCart(Request $request)
    {
        $cart = $request->session()->get('cart');
        $total_price=0;
        $total_quantity=0;

        foreach($cart as $id => $product){

            $product = $cart[$id];
            $price = $product['price'];
            $quantity = $product['quantity'];

            $total_price = $total_price + ($price*$quantity);
            $total_quantity = $total_quantity + $quantity;
        }

        $request->session()->put('total',$total_price);
        $request->session()->put('quantity',$total_quantity);
    }

   
    public function removeFromCart(Request $request)
    {
        
        
        if($request->session()->has('cart')){
                $id = $request->input('id');
                $cart = $request->session()->get('cart');

                unset($cart[$id]);
                $request->session()->put('cart',$cart);

                $this->calculateTotalCart($request);
        }

        return view('cart');
    }

    
    public function editFromCart(Request $request)
    {
        if($request->session()->has('cart')){
            $product_id=$request->input('id');
            $product_quantity=$request->input('quantity');

            if($request->has('decrease_product_quantity_btn')){
                $product_quantity = $product_quantity -1 ;
            }elseif($request->has('increase_product_quantity_btn')){
                $product_quantity = $product_quantity +1 ;
            }

            if ($product_quantity <=0) {
                $this->removeFromCart($request);
            }

            $cart = $request->session()->get('cart');

            if(array_key_exists($product_id,$cart)){
                $cart[$product_id] ['quantity'] = $product_quantity;
                $request->session()->put('cart',$cart);
                $this->calculateTotalCart($request);
            }

        }

         return view('cart');
    }

    
    public function checkout()
    {
        return view('checkout');
    }


    public function placeorder(Request $request)
    {
        if ($request->session()->has('cart')) {
            
            $name =$request->input('name');
            $email =$request->input('email');
            $phone =$request->input('phone');
            $city =$request->input('city');
            $address =$request->input('address');

            $cost = $request->session()->get('total');
            $status = 'not paid';
            $date = date('y-m-d');
            $cart = $request->session()->get('cart');

            $order_id= DB::table('orders')->InsertGetId([

                        'name'=>$name,
                        'email'=>$email,
                        'phone'=>$phone,
                        'city'=>$city,
                        'address'=>$address,
                        'cost'=>$cost,
                        'status'=>$status,
                        'date' =>$date

            ],'id');

            foreach ($cart as $id => $product) {
                    $product = $cart[$id];
                    $product_id=$product['id'];
                    $product_name=$product['name'];
                    $product_price=$product['price'];
                    $product_quantity=$product['quantity'];
                    $product_image=$product['image'];

                    DB::table('order_items')->insert([
                        'order_id'=>$order_id,
                        'product_id'=>$product_id,
                        'product_name'=>$product_name,
                        'product_image'=>$product_image,
                        'product_price'=>$product_price,
                        'product_quantity'=>$product_quantity,
                        'order_date'=>$date,
                    ]);
            }
           
            $request->session()->put('order_id' , $order_id);

            return view('payment');

        }else{
            return redirect('/');
        }
    }
}
