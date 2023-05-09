<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Consultation;
use Illuminate\Support\Facades\DB;
use App\Models\Vaccines;
use App\Models\Spot;
use App\Models\Vaccinations;

class AuthController extends Controller
{
    public function index()
    {
        return response()->json([
            "message" => "Hello World"
        ]);
    }

    public function login(Request $request)
    {
        $akun = $request->validate([
            "username" => "required",
            "password" => "required"
        ]);

        if(Auth::attempt($akun)) {
            $request->session()->regenerate();
            return response()->json([
                "Header: Response code:" => 200,
                "Body:" => $akun
            ], 200); 
        } else {
            return response()->json([
                "Header: Response code:" => 401,
                "Body:" => [
                    "Message" => "ID Card Number or Password is incorrect"
                ]
                ], 401);
        }
    }

    public function logout()
    {
        Auth::logout();

        if(Auth::logout()) {
            return response()->json([
                "Header:" => "Response code: 200",
                "Body" => [
                    "Message" => "Logout sukses"
                ],
            ], 200);
        } else {
            return response()->json([
                "Header:" => "Response code: 200",
                "Body" => [
                    "Message" => "Invalid token"
                ],
            ], 401);
        }
    }

    public function konsul(Request $request)
    {
        $konsul = Consultation::create([
            "disease_history" =>  $request->disease_history,
            "current_symptoms" => $request->current_symptoms
        ]);

        if(!$konsul) {
            response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Unauthorized user"
                ],
            ], 401);
        } else {
            response()->json([
                "Header:" => "Response code: 200",
                "Body" => [
                    "Message" => "Request consultation sent successful"
                ],
            ], 200);
        }
    }

    public function getKonsul($id)
    {
        $konsul = DB::table("consultations")->where("id", $id)->get();
        $validate = DB::table("consultations")->where("id", $id)->first();
        if(!$validate) {
             return response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Unauthorized user"
                ],
            ], 401);
        } else {
            return response()->json([
                "Header:" => "Response code: 200",
                "Body" => $konsul
            ], 200);   
        }
    }

    public function getAllSpot()
    {
        $spot = Spot::all();
        $vaccines = Vaccines::all();
        if(Auth::user()) {
            return response()->json([
                "Header:" => "Response code: 200",
                "Spots:" => $spot,
                "available_vaccines" => $vaccines
            ], 200);  
        } else { 
            return response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Unauthorized user"
                ],
            ], 401);
        }
    }

    public function getSpotId(Spot $spot)
    {
        if(Auth::user()) {
            return response()->json([
                "Header:" => "Response code: 200",
                "Spots:" => $spot,
            ], 200);  
        } else {
            return response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Unauthorized user"
                ],
            ], 401);
        }
    }

    public function VaccinesRegis(Request $request)
    {
        $vaksin = Vaccinations::create([
            "spot_id" => $request->spot_id,
            "date" => $request->date
        ]);

        $spot =  $request->spot_id;
        $date = $request->date;

        if(empty($spot) && empty($date)) {
            return response()->json([
                "Header:" => "Response code: 401",
                "Errors" => [
                    "date" => [
                        "the date does not match the format Y-m-d",
                    ],
                    "spot_id" =>[
                        "The spot_id field is required",
                    ]
                ]
            ], 401);
        }

        if(!$vaksin) {
            return response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Unauthorized user"
                ],
            ], 401);
        }elseif (strtotime($request->date) < strtotime("-30 days")) {
            return response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Wait at least +30 days from 1st Vaccination"
                ],
            ], 401);
         } else {
            return response()->json([
                "Header:" => "Response code: 200",
                "Body" => [
                    "message" => "First|Second vaccination registered successful"
                ]
            ], 200);  
         }
    }

    public function getAllVaccine()
    {
        $vaksin = Vaccinations::all();

        if(Auth::user()) {
            return response()->json([
                "Vaccinations" => $vaksin
            ]);
        } else {
            return response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Unauthorized user"
                ],
            ], 401);
        }


    }
}
