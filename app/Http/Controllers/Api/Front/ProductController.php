<?php

namespace App\Http\Controllers\Api\Front;

use App\Exceptions\NotAuthorized;
use App\Exceptions\NotFound;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ProductController extends Controller
{
    use ResponseJson;

    public function index(){
        if(Auth::user()->hasRole('vendor')){
            $products = Product::get();
            return $this->jsonResponseWithoutMessage(ProductResource::collection($products), 'data', 200);
        }
        else{
            throw new NotAuthorized;
        }
    }

    public function create(Request $request){
        if(Auth::user()->hasRole('vendor')){
            $validator = Validator::make($request->all(), [
                'name_ar' => 'required',
                'name_en' => 'required',
                'price' => 'required|numeric',
                'fill_attribute_en' => 'required',
                'fill_attribute_ar' => 'required',
                'category_id' => 'required',
                'store_id' => 'required',
                'section_id' => 'required',
                'user_id' => 'required',
                'stock_quantity' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'price_after_discount' => 'nullable|numeric',
                'is_available' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
            }
            Product::create($request->all());
            return $this->jsonResponseWithoutMessage("Created Successfully", 'data', 200);
        }
        else{
            throw new NotAuthorized;
        }
    }


    public function update(Request $request){
        if(Auth::user()->hasRole('vendor')){
            $validator = Validator::make($request->all(), [
                'name_ar' => 'required',
                'name_en' => 'required',
                'price' => 'required|numeric',
                'fill_attribute_en' => 'required',
                'fill_attribute_ar' => 'required',
                'category_id' => 'required',
                'store_id' => 'required',
                'section_id' => 'required',
                'user_id' => 'required',
                'stock_quantity' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'price_after_discount' => 'nullable|numeric',
                'is_available' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
            }
            $product= Product::find($request->id);
            if(!$product){
                throw new NotFound;
            }
            $product->update($request->all());
            return $this->jsonResponseWithoutMessage("Updated Successfully", 'data', 200);
        }
        else{
            throw new NotAuthorized;
        }

    }

    public function edit(Request $request){
        if(Auth::user()->hasRole('vendor')){
            $product= Product::find($request->id);
            if(!$product){
                throw new NotFound;
            }

            return $this->jsonResponseWithoutMessage(new ProductResource($product), 'data', 200);
        }
        else{
            throw new NotAuthorized;
        }
    }

    public function delete(Request $request){
        if(Auth::user()->hasRole('vendor')){
            $product= Product::find($request->id);
            if(!$product){
                throw new NotFound;
            }
            $product->delete();
            return $this->jsonResponseWithoutMessage("Deleted Successfully", 'data', 200);
        }
        else{
            throw new NotAuthorized;
        }
    }
}

