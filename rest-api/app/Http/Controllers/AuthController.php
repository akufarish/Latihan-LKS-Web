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

    // buat method untuk user bisa login
    public function login(Request $request)
    {
        $akun = $request->validate([
            "username" => "required",
            "password" => "required"
        ]);

        // jika login sukses maka return response sukses
        if(Auth::attempt($akun)) {
            $request->session()->regenerate();
            return response()->json([
                "Header: Response code:" => 200,
                "Body:" => $akun
            ], 200); 
        } else {
            // jika login gagal maka return response gagal
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
        // buat variable untuk insert data 
        $konsul = Consultation::create([
            // ambil data dari user input kemudian insert ke database
            "disease_history" =>  $request->disease_history,
            "current_symptoms" => $request->current_symptoms
        ]);

        // jika gagal insert data maka return response error
        if(!$konsul) {
            response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Unauthorized user"
                ],
            ], 401);
        } else {
            // jika user sukses menginputkan data maka return response sukses
            response()->json([
                "Header:" => "Response code: 200",
                "Body" => [
                    "Message" => "Request consultation sent successful"
                ],
            ], 200);
        }
    }

    // ambil data konsultasi berdasarkan id
    public function getKonsul($id)
    {
        // ambil data sesuai id yang diminta parameter
        $konsul = DB::table("consultations")->where("id", $id)->get();
        // cek apakah data ada didatabase atau tidak
        $validate = DB::table("consultations")->where("id", $id)->first();
        // jika validasi gagal maka return response error
        if(!$validate) {
             return response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Unauthorized user"
                ],
            ], 401);
        } else {
            // jika validasi berhasil maka return response sukses
            return response()->json([
                "Header:" => "Response code: 200",
                "Body" => $konsul
            ], 200);   
        }
    }

    // ambil semua data 
    public function getAllSpot()
    {
        // ambil semua data dari table spots
        $spot = Spot::all();
        // ambil semua data dari table vaccines
        $vaccines = Vaccines::all();

        // jika user sudah login maka return response sukses
        return response()->json([
            "Header:" => "Response code: 200",
            "Spots:" => $spot,
            "available_vaccines" => $vaccines
        ], 200);  
    }

    // buat method untuk mengambil data dari table spots sesuai id parameter
    public function getSpotId(Spot $spot)
    {
         return response()->json([
            "Header:" => "Response code: 200",
            "Spots:" => $spot,
        ], 200);  
    }

    // method untuk registrasi vaksin
    public function VaccinesRegis(Request $request)
    {
        // create variable untuk insert data dari input user ke dalam database

        
        $spot =  $request->input("spot_id");
        $date = $request->input("date");

        // cek jika input user kosong dan jika true maka tampilkan pesan input tidak boleh kosong
        if(empty($spot) || empty($date)) {
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

        $vaksin = Vaccinations::create([
            "spot_id" => $spot,
            "date" => $date
        ]);


        if(!$vaksin) {
            return response()->json([
                "Header:" => "Response code: 401",
                "Body" => [
                    "Message" => "Unauthorized user"
                ],
            ], 401);
            // jika tanggal yang diinputkan kurang dari 30 hari/1 bulan maka return 
            // pesan untuk menunggu 30 hari lagi dari vaksinasi pertama
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

    // ambil semua data vaksin
    public function getAllVaccine()
    {
        $vaksin = Vaccinations::all();

            return response()->json([
                "Vaccinations" => $vaksin
            ]);
    }
}
