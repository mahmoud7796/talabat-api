<?php

namespace App\Http\Controllers\Api\Front;

use App\Exceptions\NotAuthorized;
use App\Exceptions\NotFound;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserAddressResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseJson;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    protected $name;
    protected $email;

    use ResponseJson;

    public function getUserAddress(Request $request){
        $authUserId = Auth::user()->id;
         $address= User::find($request->id);
        if(!$address){
            throw new NotFound;
        }
        if($authUserId!=$address->id){
            throw new NotAuthorized;
        }
        return $this->jsonResponseWithoutMessage(new UserAddressResource($address), 'data', 200);
    }

    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
        }

        $product = Product::find($request->product_id);
        if (!$product){
            throw new NotFound;
        }
        if($request->quantity<=0){
            return $this->jsonResponseWithoutMessage("At least select one Item", 'data', 200);
        }

        if ($product->stock_quantity >= $request->quantity){
            $productId= session()->get('cart.id');
            $productQuantity= session()->get('cart.quantity');

            $arrayIndex=false;
            if($productId && count($productId)>0){
                $arrayIndex= array_search($request->product_id,$productId,true);
            }
                if(strval($arrayIndex) != ""){
                    $productQuantity[$arrayIndex]=$request->quantity;
                    session()->put('cart.quantity',$productQuantity);
                }else{
                    session()->push('cart.id',$request->product_id);
                    session()->push('cart.quantity',$request->quantity);
                    session()->push('cart.price',$request->price);
                }
            }else{
            return $this->jsonResponseWithoutMessage("Out Of Stock Or add less quantity", 'data', 200);
        }
            return $this->jsonResponseWithoutMessage('Your item Added Successfully', 'data', 200);
    }

    public function getCartData()
    {
       // $this->reIndexCart();
        $carts= session()->get('cart');
        if(isset($carts) && count($carts)>0 && isset($carts['id']) && count($carts['id'])>0){
           $subTotal=0;
           for($i=0;$i<count($carts['id']);$i++){
               $product = Product::find($carts['id'][$i]);
               if($product->stock_quantity>=$carts['quantity'][$i]){
                   $subTotal+=$carts['quantity'][$i] * $carts['price'][$i];
               }else{
                   return $this->jsonResponseWithoutMessage("Out Of Stock", 'data', 200);
               }
           }
           return $this->jsonResponseCartWithSubTotal($carts, 'data', 200,$subTotal);
       }else{
           return $this->jsonResponseWithoutMessage("No Items In Cart", 'data', 200);
       }
    }

    public function create(Request $request)
    {
        $carts = session()->get('cart');
        $validator = Validator::make($request->all(), [
            'shipping_address_ar' => 'required',
            'shipping_address_en' => 'required',
            'shipping_google_address' => 'required',
            'payment_method' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
        }

        $subTotal=0;
        for($i=0;$i<count($carts['id']);$i++){
            $product = Product::find($carts['id'][$i]);
            if($product->stock_quantity>=$carts['quantity'][$i]){
                $subTotal+=$carts['quantity'][$i] * $carts['price'][$i];
            }else{
                return $this->jsonResponseWithoutMessage("Out Of Stock", 'data', 200);
            }
        }

        $taxValue=$subTotal*(15/100);
        $shippingValue=25;
        $finalTotal=$subTotal+$taxValue+$shippingValue;

        Order::create([
            'product_attributes' => serialize($carts), //products
            'user_id' => Auth::user()->id,
            'shipping_address_ar' => $request->shipping_address_ar,
            'shipping_address_en' => $request->shipping_address_en,
            'shipping_google_address' => $request->shipping_google_address,
            'shipping_date' => now(),
            'payment_method' => $request->payment_method,
            'subtotal' => $subTotal,
            'shipping_cost' =>$shippingValue,
            'taxes' => $taxValue,
            'final_total' => $finalTotal,
            'is_notified' => 0,
        ]);

        for($i=0;$i<count($carts['id']);$i++){
            $product = Product::find($carts['id'][$i]);
            $product->stock_quantity-=$carts['quantity'][$i];
            $product->save();
        }

        $user=Auth::user();
        $this->email=$user->email;
        $this->name=$user->name;
        $data = array('user'=>$user);
        Mail::send('mail', $data, function($message) {
            $message->to($this->email, $this->name)->subject
            ('Laravel HTML Testing Mail');
            $message->from('xyz@gmail.com','Virat Gandhi');
        });

        return $this->jsonResponseWithoutMessage("Created Successfully", 'data', 200);

    }
    public function removeFromCart(Request $request)
    {
        $product= Product::find($request->product_id);
        if(!$product){
            throw new NotFound;
        }
        $carts = session()->get('cart');
        $productCartId= session()->get('cart.id');
        $productCartQuantity= session()->get('cart.quantity');
        $productCartPrice= session()->get('cart.price');

        if($carts && count($carts)>0){
            if($productCartId && count($productCartId)>0){
                $arrayIndex= array_search($request->product_id,$productCartId,true);
                if(strval($arrayIndex)!= ""){
                    array_splice($productCartId, $arrayIndex,1);
                    array_splice($productCartQuantity, $arrayIndex,1);
                    array_splice($productCartPrice, $arrayIndex,1);
                    session()->put('cart.id', $productCartId);
                    session()->put('cart.quantity', $productCartQuantity);
                    session()->put('cart.price', $productCartPrice);
                }else{
                    return $this->jsonResponseWithoutMessage("This product does not exist in cart", 'data', 200);
                }
            }
            return $this->jsonResponseWithoutMessage("item removed successfully", 'data', 200);

        }else{
            return $this->jsonResponseWithoutMessage("There is no item to remove", 'data', 200);
        }
    }

    public function show(Request $request){
        $order = Order::find($request->id);
        if(!$order){
            throw new NotFound;
        }
        if(Auth::user()->hasRole('user')){
           if( Auth::user()->id!=$order->user_id){
               throw new NotAuthorized;
           }
        }
        return $this->jsonResponseWithoutMessage(new OrderResource($order), 'data', 200);
    }

    public function updateStatus(Request $request)
    {
        if (Auth::user()->hasRole('vendor') || Auth::user()->hasRole('admin')) {
        $orderStatus = Order::find($request->id);
        if (!$orderStatus) {
            throw new NotFound;
        }
        $orderStatus->update([
            'order_status' => $request->order_status
        ]);
        $user = User::find($orderStatus->user_id);
        $this->email = $user->email;
        $this->name = $user->name;
        $data = array('user' => $user);
        Mail::send('mail', $data, function ($message) {
            $message->to($this->email, $this->name)->subject
            ('Your Order Is Completed');
            $message->from('xyz@gmail.com', 'Virat Gandhi');
        });
        return $this->jsonResponseWithoutMessage('Order Status Updated Successfully', 'data', 200);
    }else{
            throw new NotAuthorized;
        }
    }
}
