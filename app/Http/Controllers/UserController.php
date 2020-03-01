<?php

namespace App\Http\Controllers;
use App\ModelCd;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function index(Request $request){
        $data = ModelCd::all();
        return response($data);
    }
    public function insertCd (Request $request){
        $data = new ModelCd();
        $data->title = $request->input('title');
        $data->rate = $request->input('rate');
        $data->category = $request->input('category');
        $data->quantity = $request->input('quantity');
        $data->save();
    
        return response($data);
    }
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
    public function getCd ($id) {
        $data = ModelCd::where('id', $id)->first();
        return response($data);
    }
    public function searchCd(Request $request){
        $data = ModelCd::all();
        if (null !== ($request->has('title'))){
            $search = $request->title;
            $data = $data->where('title', 'like', $search)->all();
        }
        if (null !== ($request->has('category'))){
            $search = $request->category;
            $data = $data->where('category', 'like', $search)->all();
        }
        return response($data);
    }
}
