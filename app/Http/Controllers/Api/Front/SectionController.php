<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Section;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\See;
use App\Http\Resources\SectionResource;
class SectionController extends Controller
{
    use ResponseJson;

    public function index()
    {
        $sections = Section::all();
        if($sections){
            return response()->json(['sections' => SectionResource::collection($sections)], 200);
        }

    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'vendor_id' => 'required|numeric',
            'section_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
        }
        Section::create($request->all());
        return $this->jsonResponseWithoutMessage("Section Craeted Successfully", 'data', 200);
    }

    public function show(Request $request)
    {
        $section = Section::find($request->section_id);
        if($section){
            return response()->json(['section' => new SectionResource($section)], 200);
        }
        throw new \App\Exceptions\NotFound;
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'vendor_id' => 'required|numeric',
            'section_id' => 'required|numeric',
            'user_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
        }
        $section = Section::find($request->section_id);
        $section->update($request->all());
        //$section->save();
        return $this->jsonResponseWithoutMessage("Section Updated Successfully", 'data', 200);
    }//update

    public function destroy(Request $request)
    {
        $section = Section::find($request->section_id);
        $section->delete();
        return $this->jsonResponseWithoutMessage("Section Deleted Successfully", 'data', 200);

    }//destroy


}
