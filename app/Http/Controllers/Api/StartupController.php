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
           
            // $validator = Validator::make($request->all(), [
            //     'email' => 'required|email|unique:users',
            //     'phone_no' => 'required|string|min:10|max:16',
            //     'gender' => 'required',
            //     'city' => 'required',
            //     'country' => 'required',
            //     'linkedin_url' => 'required|url'
            // ]);
            // if ($validator->fails()) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Validation error',
            //         'errors' => $validator->errors(),
            //     ], 422);
            // } 
            // else {
                // Store the user in the database
              $user=  User::where('id', $request->id)->update([
                'email' => $request->email,
                'gender' =>$request->gender,
                'linkedin_url' => $request->linkedin_url,
                'city' => $request->city,
                'phone' => $request->phone,
                'country' => $request->countries,
                'reg_step_1'=>'1',
                'updated_at' => Carbon::now(),
             ]);
                $user_id = User::find($request->id);
        

                $token = JWTAuth::fromUser($user_id);
            // }
            return response()->json(['status' => true, 'message' => 'Personal Details stored successfully', 'data' => ['user' => $user,'token',$token]], 200);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
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
        // $data=$request->all();
        $data                =  new Business();
        $data->user_id       = $request->id;
        $data->business_name = $request->business_name;
        $data->reg_businessname =$request->reg_businessname;
        $data->website_url   =  $request->website_url;
        $data->stage         =  $request->stage;
        $data->department    =  $request->department;
        $data->startup_date    =  $request->startup_date;
        $data->description   =  $request->description;
        $data->primary_residence=$request->primary_residence;
        $data->prev_experience =$request->prev_experience;
        $data->experience     = $request->experience;
        $data->cofounder     = $request->cofounder;
        if ($request->hasFile('logo')) {
            $randomNumber = mt_rand(1000000000, 9999999999);
            $imagePath = $request->file('logo');
            $imageName = $randomNumber . $imagePath->getClientOriginalName();
            $imagePath->move('images/profile', $imageName);
            $data->logo = $imageName;
        }
        $data->none_select   =$request->none_select;
        $data->kyc_purposes  = $request->kyc_purposes;
        $data->tagline       = $request->tagline;
        $data->sector        = $request->sector;
        $data->updated_at    =Carbon::now();
        $data->save();
      
            $user=User::where('id',$request->id)->update(['reg_step_2'=>'1']);
        
        
        return response()->json(['status' => true, 'message' => 'Business Details stored successfully', 'data' => ['data' => $data]], 200);
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
    public function store(Request $request)
    {
        //
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
