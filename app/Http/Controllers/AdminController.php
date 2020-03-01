<?php

namespace App\Http\Controllers;
use App\ModelCd;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance for Admin.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    // Insert new CD
    public function insertCd (Request $request){
        $data = new ModelCd();
        $data->title = $request->input('title');
        $data->rate = $request->input('rate');
        $data->category = $request->input('category');
        $data->quantity = $request->input('quantity');
        $data->save();
    
        return response($data);
    }
    // Edit existing CD
    public function editCd ($id, Request $request){
        $data = ModelCd::where('id',$id)->first();
        if (null !== ($request->input('title'))) {
            $data->title = $request->input('title');
        }
        if (null !== ($request->input('rate'))) {
            $data->rate = $request->input('rate');
        }
        if (null !== ($request->input('category'))) {
            $data->category = $request->input('category');
        }
        if (null !== ($request->input('quantity'))) {
            $data->quantity = $request->input('quantity');
        }
        $data->save();
    
        return response($data);
    }
    // Delete Existing CD
    public function deleteCd ($id, Request $request){
        $data = ModelCd::where('id',$id)->first();
        $data->delete();
    
        return response('CD deleted');
    }
}
