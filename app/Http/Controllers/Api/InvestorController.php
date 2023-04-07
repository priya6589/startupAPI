<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\User;
use App\Models\Business;
use App\Models\BankDetails;

class InvestorController extends Controller
{
    public function investor_profile(Request $request)
    {
        try {

            // $validator = Validator::make($request->all(), [
            //     'firstname' => 'required|max:255',
            //     'lastname' => 'required|max:255',
            //     'email' => 'required|email',
            //     'phone' => 'required|min:10|max:20',
            //     'gender' => 'required',
            //     'city' => 'required',
            //     'country' => 'required',
            //     'linkedinurl' => 'required|url'
            // ]);
            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Validation error',
            //         'errors' => $validator->errors(),
            //     ], 200);
            // } else {
                // Store the user in the database
                $user = User::find($request->id);
                $user->name = $request->firstname . " " . $request->lastname;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->phone = $request->phone;
                $user->gender = $request->gender;
                $user->city = $request->city;
                $user->country = $request->country;
                $user->linkedin_url = $request->linkedinurl;
                $data  = $user->save();
                // $otp = VerificationCode::create([
                //     'user_id' => 1,
                //     'otp' => rand(1000, 9999),
                //     'expire_at' => Carbon::now()->addMinutes(1)
                // ]);
                if($data){
                   return response()->json(['status' => true, 'message' => 'User profile stored successfully', 'data' => $user], 200);
                }else{
                     return response()->json(['status' => flase, 'message' => 'User profile not stored successfully', 'data' => 'error'], 200);
                }
            // }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
}
