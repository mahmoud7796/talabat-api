<?php

namespace App\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use App\Models\category;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    use ResponseJson;

    public function index()
    {
         $categories = category::all();
        if($categories){
            return response()->json(['categories' => CategoryResource::collection($categories)], 200);
        }

    }

   public function listCategoriesOfVendorSection(Request $request)
    {
        $listCategoriesOfVendorSection = Category::whereVendorId($request->vendor_id)->whereSectionId($request->section_id)->get();
        return $this->jsonResponseWithoutMessage(CategoryResource::collection($listCategoriesOfVendorSection), 'data', 200);
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'vendor_id' => 'required|numeric',
            'attributes' => 'required',
            'section_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
        }
        category::create($request->all());
        return $this->jsonResponseWithoutMessage("Category Created Successfully", 'data', 200);
    }

    public function show(Request $request)
    {
        $category = category::find($request->category_id);
        if($category){
            return response()->json(['category' => new CategoryResource($category)], 200);
        }
        throw new \App\Exceptions\NotFound;
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'vendor_id' => 'required|numeric',
            'attributes' => 'required',
            'section_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->jsonResponseWithoutMessage($validator->errors(), 'data', 500);
        }
        $category = category::find($request->category_id);
        $category->update($request->all());
        return $this->jsonResponseWithoutMessage("Category Updated Successfully", 'data', 200);
    }//update

    public function destroy(Request $request)
    {
        $category = category::find($request->category_id);
        $category->delete();
        return $this->jsonResponseWithoutMessage("Category Deleted Successfully", 'data', 200);

    }//destroy


}
