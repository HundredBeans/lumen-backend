<?php

namespace App\Http\Controllers;
use App\ModelCd;
use App\ModelRent;
use App\User;
use Illuminate\Http\Request;

/**
 * Create a new controller instance for Admin.
 */
class AdminController extends Controller
{
    /**
     * Add middleware Authentication for Admin using admin_token
     */
    public function __construct()
    {
        // Middleware Auth for admin
        $this->middleware("admin");
    }
    /**
     * Insert new CD by Admin
     */
    public function insertCd (Request $request){
        $data = new ModelCd();
        $data->title = $request->input('title');
        $data->rate = $request->input('rate');
        $data->category = $request->input('category');
        $data->quantity = $request->input('quantity');
        $data->save();
    
        return response()->json([
            'success'=>true,
            'message'=>'Add new CD success',
            'data'=>$data
        ], 201);
    }
    /**
     * Edit CD by Admin with parameter CD_ID
     */
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
    
        return response()->json([
            'success' => true,
            'message' => 'Edit existing CD success',
            'data' => $data
        ], 201);
    }
    /**
     * Soft Delete Existing CD by change its quantity to 0
     */
    public function deleteCd ($id){
        $data = ModelCd::where('id',$id)->first();
        $data->quantity = 0;
        $data->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Delete CD success'
        ], 200);
    }
    /**
     * Show list of returned CD
     */
    public function getListRentReturned(){
        $rentList = ModelRent::all();
        $rentDetails = array();
        foreach ($rentList as $rent) {
            if ($rent['returned'] == true){
                $cdID = $rent['cd_id'];
                $userID = $rent['user_id'];
                $cd = ModelCd::where('id', $cdID)->first();
                $user = User::where('id', $userID)->first();
                $rentDetail = array(
                    'rent_id' => $rent['id'],
                    'borrower_name' => $user->name,
                    'borrower_username' => $user->username,
                    'title' => $cd->title,
                    'category' => $cd->category,
                    'rent_date' => $cd->created_at,
                    'return_date' => $cd->updated_at,
                );
                array_push($rentDetails, $rentDetail);
            }else{
                continue;
            }
        };
        return response()->json([
            'total_rent' => count($rentDetails),
            'detail' => $rentDetails
        ], 200); 
    }
    /**
     * Show list of not yet returned CD
     */
    public function getListRentNotReturned(){
        $rentList = ModelRent::all();
        $rentDetails = array();
        foreach ($rentList as $rent) {
            if ($rent['returned'] == false){
                $cdID = $rent['cd_id'];
                $userID = $rent['user_id'];
                $cd = ModelCd::where('id', $cdID)->first();
                $user = User::where('id', $userID)->first();
                $rentDetail = array(
                    'rent_id' => $rent['id'],
                    'borrower_name' => $user->name,
                    'borrower_username' => $user->username,
                    'title' => $cd->title,
                    'category' => $cd->category,
                    'rent_date' => $cd->created_at,
                    'return_date' => 'N/A',
                );
                array_push($rentDetails, $rentDetail);
            }else{
                continue;
            }
        };
        return response()->json([
            'total_rent' => count($rentDetails),
            'detail' => $rentDetails
        ], 200); 
    }
}
