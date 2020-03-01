<?php

namespace App\Http\Controllers;
use App\ModelCd;
use App\ModelRent;
use App\User;
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
        // Middleware Auth for admin
        $this->middleware("admin");
    }
    // Insert new CD
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
    
        return response()->json([
            'success' => true,
            'message' => 'Edit existing CD success',
            'data' => $data
        ], 201);
    }
    // Delete Existing CD
    public function deleteCd ($id, Request $request){
        $data = ModelCd::where('id',$id)->first();
        $data->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Delete CD success'
        ]);
    }
    // Show list returned rent
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
    // Show list rent not yet returned
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
