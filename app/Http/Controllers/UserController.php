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
        // Middleware Auth for User
        $this->middleware("login");
    }
    // Get CD List
    public function getListCd(Request $request){
        $data = ModelCd::all();
        return response($data);
    }
    // Get specific CD
    public function getCd ($id) {
        $data = ModelCd::where('id', $id)->first();
        return response($data);
    }
    

}
