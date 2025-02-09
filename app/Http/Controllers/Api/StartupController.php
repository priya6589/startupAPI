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
use App\Models\CoFounder;
use App\Models\About;
use App\Models\Contact;
use Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\VerificationCode;
use Carbon\Carbon;
class StartupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function personal_information(Request $request){
        try {
           
            $validator = Validator::make($request->all(), [
                // 'country_code' => 'required|string',
                'phone' => 'required',
                'gender' => 'required',
                'city' => 'required',
                'country' => 'required',
                'linkedin_url' => 'required|url', 
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            } 
            else {
                // Store the user in the database
            $user = User::find($request->id);
                        $user->email = $request->email;
                        $user->gender = $request->gender;
                        $user->linkedin_url = $request->linkedin_url;
                        $user->gender = $request->gender;
                        $user->city = $request->city;
                        $user->phone =
                        $request->phone;
                        $user->country_code= $request->country_code;
                        $user->country =$request->country;
                        $user->reg_step_1 = '1';
                        $user->save();
                        
            return response()->json(['status' => true, 'message' => 'Profile updated successfully', 'data' => ['user' => $request->country]], 200);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
            return response()->json(['success' => true, 'message' => 'Error Occuring.'], 500);
            
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function business_information(Request $request)
    { 
        try {
        $userId = $request->user_id;
        $data  = Business::where('user_id', $userId)->first();
        if ($data ) {
            $data ->update($request->all());
            return response()->json(['status' => true, 'message' => 'Business Details updated successfully', 'data' => ['data' => $data]], 200);
        } else {
            $data                =  new Business();
            $data->user_id       =    $userId;
            $data->business_name = $request->business_name;
            $data->reg_businessname =$request->reg_businessname;
            $data->website_url   =  $request->website_url;
            $data->stage         =  $request->stage;
            // $data->department    =  $request->department;
            $data->startup_date    =  $request->startup_date;
            $data->description   =  $request->description;
            $data->cofounder     = $request->cofounder;
            // if ($request->hasFile('logo')) {
            //     $randomNumber = mt_rand(1000000000, 9999999999);
            //     $imagePath = $request->file('logo');
            //     $imageName = $randomNumber . $imagePath->getClientOriginalName();
            //     $imagePath->move('images/profile', $imageName);
            //     $data->logo = $imageName;
            // }
            $data->logo          =$request->logo;
            $data->kyc_purposes  = $request->kyc_purposes;
            $data->tagline       = $request->tagline;
            $data->sector        = $request->sector;
            $data->updated_at    =Carbon::now();
            $data->save();
            $user=User::where('id', $userId)->update(['reg_step_2'=>'1']);
            return response()->json(['status' => true, 'message' => 'Business Details stored successfully', 'data' => ['data' => $data]], 200);
        }
        
        
    } 
    catch (\Exception $e) {
        throw new HttpException(500, $e->getMessage());
    }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_business_information(Request $request)
    {
        try {
            $userId = $request->id;
            $data  = Business::where('user_id', $userId)->first();
            if ($data) {
                $data  = Business::where('user_id', $userId)->first();
                    return response()->json(['status' => true, 'message' => "Data fetching successfully", 'data' => $data], 200);
                }
            // $data = Business::where('user_id', $request->id)->first();
            // if ($data) {
            //     return response()->json(['status' => true, 'message' => "Data fetching successfully", 'data' => $data], 200);
            // } else {
            //     return response()->json(['status' => false, 'message' => "There has been error for fetching the business data.", 'data' => ""], 400);
            // }
        } catch (\Exception $e) {
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
