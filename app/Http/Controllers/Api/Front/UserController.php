<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Exceptions\NotFound;
class UserController extends Controller
{
    //
        use ResponseJson;
    public function index()
    {

        $users = User::all();
        return $this->jsonResponseWithoutMessage(UserResource::collection($users), 'data', 200);


    }

    public function show(Request $req)
    {
        $user=User::find($req->id);
        if (!$user) {
            throw new NotFound;
        }
        return $this->jsonResponseWithoutMessage(new UserResource($user), 'data', 200);
    }

    public function create(Request $req){

         if(Auth::user()->hasRole('admin')|| Auth::user()->hasRole('vendor')){
            $validator = Validator::make($req->all(), [
                'name_ar' => 'required',
                'name_en' => 'required',
                'phone' => 'required',
                'address_en' => 'nullable',
                'address_ar' => 'nullable',
                'google_address' => 'nullable',
                'user_type' => 'required',
                'email' => 'required|email|unique:users,email',
                'password'=>'required',
            ]);
            if ($validator->fails()) {
                return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
            }

            $user=User::create($req->all());
            $user->assignRole($req->user_type);

            return $this->jsonResponseWithoutMessage("Created Successfully", 'data', 200);
        }
         else{
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
            'google_address' => 'nullable',
            'user_type' => 'string',
            'email' => 'email|unique:users,email',
            'password' => 'string',

        ]);




        if ($validator->fails()) {
            return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
        }
        if (Auth::user()->id != $req->id) {
            if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('vendor')) {
                $user = User::find($req->id);
                 if (!$user) {
                throw new NotFound;
            }
                if($req->user_type){
                 $user->assignRole($req->user_type);
            }

                $user->update($req->all());
                return $this->jsonResponseWithoutMessage(new UserResource($user), 'data', 200);

            } else {
                throw new \App\Exceptions\NotAuthorized;
            }

        }else{
            $user = User::find($req->id);
            if (!$user) {
                throw new NotFound;
            }
            if($req->user_type){
                 $user->assignRole($req->user_type);
            }

            $user->update($req->all());
            return $this->jsonResponseWithoutMessage("updated Successfully", 'data', 200);

        }



    }
    public function delete(Request $req)
    {
      if (Auth::user()->id != $req->id) {
         if (Auth::user()->hasRole('admin')|| Auth::user()->hasRole('vendor')) {
            $user = User::find($req->id);
                if (!$user) {
                    throw new NotFound;
                }
            $user->delete();
            return $this->jsonResponseWithoutMessage("Deleted Successfully", 'data', 200);
        } else {
            throw new \App\Exceptions\NotAuthorized;
        }
    }else{
            $user = User::find($req->id);
            if (!$user) {
                throw new NotFound;
            }
            $user->delete();
            return $this->jsonResponseWithoutMessage("Deleted Successfully", 'data', 200);

    }

}
}