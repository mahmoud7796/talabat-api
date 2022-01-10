<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\VendorResource;
use Illuminate\Http\Request;
use App\Traits\ResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\vendor;
use App\Models\User;
use App\Exceptions\NotFound;
class VendorController extends Controller
{
    //
    use ResponseJson;
    public function index()
    {

        $vendors = vendor::all();
        return $this->jsonResponseWithoutMessage(VendorResource::collection($vendors), 'data', 200);
    }

    public function show(Request $req)
    {

        $vendor = Vendor::find($req->id);
         if (!$vendor) {
                throw new NotFound;
            }

        return $this->jsonResponseWithoutMessage(new VendorResource($vendor), 'data', 200);
    }

    public function create(Request $request)
    {

        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('vendor')) {
            $validator = Validator::make($request->all(), [
                'name_ar' => 'required',
                'name_en' => 'required',
                'phone' => 'required',
                'address_en' => 'nullable',
                'address_ar' => 'nullable',
                'user_id' => 'required',
                'email' => 'required|email|unique:users,email',
                'is_blocked' => 'required',
                'type_id'=>'required'

            ]);


            if ($validator->fails()) {
                return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
            }
            Vendor::create($request->all());
            return $this->jsonResponseWithoutMessage("Created Successfully", 'data', 200);
        } else {
            throw new \App\Exceptions\NotAuthorized;
        }
    }

    public function update(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'name_ar' => 'string',
            'name_en' => 'string',
            'phone' => 'string',
            'address_en' => 'nullable',
            'address_ar' => 'nullable',
            'user_id'=>'string',
            'email' => 'email|unique:users,email',
            'is_blocked' =>'string',
            'type_id'=>'string'

        ]);
        if ($validator->fails()) {
            return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
        }

            if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('vendor')) {
                $vendor = vendor::find(Auth::user()->id);
            if (!$vendor) {
                throw new NotFound;
            }
                $vendor->update($req->all());
                return $this->jsonResponseWithoutMessage(new VendorResource($vendor), 'data', 200);
            } else {
                throw new \App\Exceptions\NotAuthorized;
            }


    }
    public function delete(Request $req)
    {
       if(Auth::user()->id==$req->id || Auth::user()->hasRole('admin')){

                $vendor = Vendor::find($req->id);
            if (!$vendor) {
                throw new NotFound;
            }
                $vendor->delete();
                return $this->jsonResponseWithoutMessage("Deleted Successfully", 'data', 200);}
      else {
                throw new \App\Exceptions\NotAuthorized;
       }

         if (Auth::user()->hasRole('admin') ) {
                $vendor = Vendor::find($req->id);
            if (!$vendor) {
                throw new NotFound;
            }
                $vendor->delete();
                return $this->jsonResponseWithoutMessage("Deleted Successfully", 'data', 200);

         }
          else {
                throw new \App\Exceptions\NotAuthorized;
       }


}
public function isblocked(Request $req){

 if (Auth::user()->hasRole('admin')) {

     $vendor = Vendor::find($req->id);
         if (!$vendor) {
                throw new NotFound;
        }

     if($vendor->is_blocked==0){
            $vendor->is_blocked=1;
            $vendor->save();
             return $this->jsonResponseWithoutMessage("now vendor is blocked", 'data', 200);

     }
     else{
       return $this->jsonResponseWithoutMessage("vendor was blocked", 'data', 200);
     }

 }else{
     throw new \App\Exceptions\NotAuthorized;
 }

}
public function isnotblocked(Request $req){
 if (Auth::user()->hasRole('admin') ) {

     $vendor = Vendor::find($req->id);
    if (!$vendor) {
          throw new NotFound;
      }
     if($vendor->is_blocked==0){

             return $this->jsonResponseWithoutMessage("vendor wasn't blocked", 'data', 200);

     }
     else{
         $vendor->is_blocked=0;
          $vendor->save();
        return $this->jsonResponseWithoutMessage("now vendor isn't blocked", 'data', 200);
     }

 }else{
     throw new \App\Exceptions\NotAuthorized;
 
}
}
}
