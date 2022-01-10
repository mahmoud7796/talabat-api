<?php

namespace App\Http\Controllers\Api\Front;

use App\Exceptions\NotAuthorized;
use App\Exceptions\NotFound;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\TypeResource;
use App\Models\Product;
use App\Models\Type;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    use ResponseJson;

    public function index(){
        if(Auth::user()->hasRole('vendor')){
            $types = Type::get();
            return $this->jsonResponseWithoutMessage(TypeResource::collection($types), 'data', 200);
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
            ]);

            if ($validator->fails()) {
                return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
            }
            Type::create($request->all());
            return $this->jsonResponseWithoutMessage("Created Successfully", 'data', 200);
        }
        else{
            throw new NotAuthorized;
        }
    }

    public function edit(Request $request){
        if(Auth::user()->hasRole('vendor')){
            $type = Type::find($request->id);
            if(!$type){
                throw new NotFound;
            }
            return $this->jsonResponseWithoutMessage($type, 'data', 200);
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
            ]);

            if ($validator->fails()) {
                return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
            }
            $type= Type::find($request->id);
            if(!$type){
                throw new NotFound;
            }
            $type->update($request->all());
            return $this->jsonResponseWithoutMessage("Updated Successfully", 'data', 200);
        }
        else{
            throw new NotAuthorized;
        }

    }

    public function delete(Request $request){
        if(Auth::user()->hasRole('vendor')){
            $type= Type::find($request->id);
            if(!$type){
                throw new NotFound;
            }
            $type->delete();
            return $this->jsonResponseWithoutMessage("Deleted Successfully", 'data', 200);
        }
        else{
            throw new NotAuthorized;
        }
    }
}
