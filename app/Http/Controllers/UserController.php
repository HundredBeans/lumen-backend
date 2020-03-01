<?php

namespace App\Http\Controllers;
use App\ModelCd;
use App\User;
use App\ModelRent;
use DateTime;
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
    // Rent specific CD
    public function rentCd($id, Request $request){
        // Find user
        $token = explode(" ", $request->header('Authorization'));
        $user =  User::where('token', $token[1])->first();
        $userID = $user->id;
        // Find cd
        $cd = ModelCd::where('id', $id)->first();
        $cdID = $cd->id;
        // Reduce cd quantity by 1
        if ($cd->quantity > 0) {
            $cd->quantity -= 1;
            $cd->save();
        }else{
            return response()->json([
                'success' => false,
                'message' => 'CD is out of stock',
                'data' => ''
            ], 400);
        }
        // Make new rent
        $rent = new ModelRent();
        $rent->user_id = $userID;
        $rent->cd_id = $cdID;
        $rent->returned = false;
        $rent->save();

        return response()->json([
            'success' => true,
            'message' => 'rent success',
            'borrower' => $user->name,
            'data' => [
                'rent_detail' => $rent,
                'cd_detail' => [
                    'title' => $cd->title,
                    'rate' => $cd->rate,
                    'category' => $cd->category,
                ],
            ]
        ], 200);

    }
    // Check List User's Rented CD
    public function checkListUserRent(Request $request) {
        // Find user
        $token = explode(" ", $request->header('Authorization'));
        $user =  User::where('token', $token[1])->first();
        $userID = $user->id;
        // Find User's Rent
        $rent = ModelRent::where('user_id', $userID)->get();
        $cd_details = array();
        foreach ($rent as $value) {
            if ($value['returned']==false){
                $cdID = $value['cd_id'];
                $cd = ModelCd::where('id', $cdID)->first();
                $cd_detail = array('rent_id'=>$value->id, 'title'=>$cd->title, 'rate'=>$cd->rate, 'category'=>$cd->category, 'rent_date'=>$cd->created_at);
                array_push($cd_details, $cd_detail);
            }else{
                continue;
            }
        }
        return response()->json([
            'borrower' => $user->name,
            'total_rent'=>count($cd_details),
            'rent_detail'=>$cd_details
        ], 200);
    }
    // Check User's Rented CD by ID
    public function checkUserRent($id, Request $request) {
        // Find user
        $token = explode(" ", $request->header('Authorization'));
        $user =  User::where('token', $token[1])->first();
        $userID = $user->id;
        // Find User's Rent by ID
        $rent = ModelRent::where('user_id', $userID)->get();
        $rent = $rent::where('id', $id)->first();
        $cdID = $rent->id;
        // Find CD from cd_id in Rent
        $cd = ModelCd::where('id', $cdID)->first();
        if ($rent->returned == false) {
            return response()->json([
                'borrower' => $user->name,
                'data'=>[
                    'rent_id'=>$id,
                    'title'=>$cd->title,
                    'rate'=>$cd->rate,
                    'category'=>$cd->category,
                    'rent_date'=>$rent->created_at,
                    'return_status'=>$rent->returned,
                ]
                ], 200);
        }else{
            return response()->json([
                'message'=>"CD is already returned.",
                'data'=>''
            ], 400);
        }
    }
    // Return User's Rented CD
    public function returnUserRent($id, Request $request) {
        // Find user
        $token = explode(" ", $request->header('Authorization'));
        $user =  User::where('token', $token[1])->first();
        $userID = $user->id;
        // Find User's Rent by ID
        $rent = ModelRent::where('user_id', $userID)->get();
        $rent = $rent->where('id', $id)->first();
        $cdID = $rent->cd_id;
        // Find CD rate
        $cd = ModelCd::where('id', $cdID)->first();
        $cdRate = $cd->rate;
        if ($rent->returned == true){
            return response()->json([
                'message'=>'CD is already returned'
            ], 400);
        }
        $rent->returned = true;
        $rent->save();
        // Calculate Rent day
        $rentDate = $rent -> created_at;
        $dateNow = new DateTime();
        $totalRentDay = $dateNow->diff($rentDate)->format('%d');
        // Calculate rent price
        $rentPrice = ($totalRentDay + 1) * $cdRate;

        return response()->json([
            'success'=>true,
            'message'=>'Return CD success',
            'borrower' => $user->name,
            'rent_price'=>$rentPrice,
            'rent_detail'=>[
                'rent_id'=>$id,
                'title'=>$cd->title,
                'rate'=>$cd->rate,
                'category'=>$cd->category,
                'rent_date'=>$rent->created_at,
            ]
            ], 200);
    }
}
