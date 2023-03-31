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

class UserController extends Controller
{
    public function startup_register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|max:16|confirmed',
                'phone_no' => 'required|string|min:10|max:16',
                'gender' => 'required',
                'city' => 'required',
                'country' => 'required',
                'linkedin_url' => 'required|url',
                'profile_pic' => 'required|image',
                'profile_desc' => 'required|string',
                'website_url' => 'required|url',
                'business_name' => 'required|max:255',
                'reg_businessname' => 'required|max:255',
                'sector' => 'required|max:255',
                'stage' => 'required|max:255',
                'logo' => 'required|image',
                'description' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            } else {
                // Store the user in the database
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->phone_no = $request->phone_no;
                $user->gender = $request->gender;
                $user->city = $request->city;
                $user->country = $request->country;
                $user->linkedin_url = $request->linkedin_url;
                if ($request->hasFile('profile_pic')) {
                    $randomNumber = mt_rand(1000000000, 9999999999);
                    $imagePath = $request->file('profile_pic');
                    $imageName = $randomNumber . $imagePath->getClientOriginalName();
                    $imagePath->move('images/profile', $imageName);
                    $user->profile_pic = $imageName;
                }
                $user->profile_desc = $request->profile_desc;
                $user->save();

                $business = new Business();
                $business->business_name = $request->business_name;
                $business->user_id = $user->id;
                $business->reg_businessname = $request->reg_businessname;
                $business->website_url = $request->website_url;
                $business->sector = $request->sector;
                $business->stage = $request->stage;
                $business->startup_date = $request->startup_date;
                if ($request->hasFile('logo')) {
                    $randomNumber = mt_rand(1000000000, 9999999999);
                    $imagePath = $request->file('logo');
                    $imageName = $randomNumber . $imagePath->getClientOriginalName();
                    $imagePath->move('images/profile', $imageName);
                    $business->logo = $imageName;
                }
                $business->description = $request->description;
                $data = $business->save();
            }
            return response()->json(['status' => true, 'message' => 'User register successfully', 'error' => '', 'data' => ''], 200);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
    public function investor_register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|max:16|confirmed',
                'phone_no' => 'required|string|min:10|max:16',
                'gender' => 'required',
                'city' => 'required',
                'country' => 'required',
                'linkedin_url' => 'required|url',
                'profile_desc' => 'required|string'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            } else {
                // Store the user in the database
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->phone_no = $request->phone_no;
                $user->gender = $request->gender;
                $user->city = $request->city;
                $user->country = $request->country;
                $user->linkedin_url = $request->linkedin_url;
                $user->profile_desc = $request->profile_desc;
                $data = $user->save();
                return response()->json(['status' => true, 'message' => 'User register successfully', 'error' => '', 'data' => $data], 200);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
    public function user_login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return response()->json(['status' => false, 'message' => 'user logedin', 'data' => $credentials], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Please enter correct credentials!', 'error' => '', 'data' => ''], 200);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
    public function update_profile(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->name =  $request->name;
            $user->phone_no = $request->phone_no;
            $user->gender =  $request->gender;
            $user->city = $request->city;
            $user->country = $request->country;
            $user->linkedin_url = $request->linkedin_url;
            if ($request->hasFile('profile_pic')) {
                $randomNumber = mt_rand(1000000000, 9999999999);
                $imagePath = $request->file('profile_pic');
                $imageName = $randomNumber . $imagePath->getClientOriginalName();
                $imagePath->move('images/profile', $imageName);
                $user->profile_pic = $imageName;
            }
            $savedata = $user->save();
            if ($savedata) {
                return response()->json(['status' => true, 'message' => "Profile has been updated succesfully", 'data' => $savedata], 200);
            } else {
                return response()->json(['status' => false, 'message' => "There has been error for updating the profile", 'data' => ""], 200);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
    public function store_bank_detail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bank_name' => 'required',
                'account_holder' => 'required|min:4|max:16',
                'account_no'   => 'required|min:12|max:16',
                'ifsc_code'   => 'required|min:11|max:15'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }
            $bank = new BankDetails();
            $bank->user_id = $request->user_id;
            $bank->business_id = $request->business_id;
            $bank->bank_name = $request->bank_name;
            $bank->account_holder = $request->account_holder;
            $bank->account_no = $request->account_no;
            $bank->ifsc_code = $request->ifsc_code;
            $savedata = $bank->save();
            if ($savedata) {
                return response()->json(['status' => true, 'message' => "Bank detail has been stored succesfully", 'data' => $savedata], 200);
            } else {
                return response()->json(['status' => false, 'message' => "There has been error for storing the bank detail", 'data' => ""], 400);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
    public function update_bank_detail(Request $request)
    {
        try {
            $bank = BankDetails::find($request->id);
            $bank->user_id = $request->user_id;
            $bank->business_id = $request->business_id;
            $bank->bank_name = $request->bank_name;
            $bank->account_holder = $request->account_holder;
            $bank->account_no = $request->account_no;
            $bank->ifsc_code = $request->ifsc_code;
            $savedata = $bank->save();
            if ($savedata) {
                return response()->json(['status' => true, 'message' => "Bank detail has been updated succesfully", 'data' => $savedata], 200);
            } else {
                return response()->json(['status' => false, 'message' => "There has been error for updating the bank detail", 'data' => ""], 400);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
    public function business_detail_update(Request $request)
    {
        try {
            $user = User::where('id', $request->id)->first();
            if ($user) {
                $business = Business::find($request->id);
                $business->business_name = $request->business_name;
                $business->user_id = $user->id;
                $business->reg_businessname = $request->reg_businessname;
                $business->website_url = $request->website_url;
                $business->sector = $request->sector;
                $business->stage = $request->stage;
                $business->startup_date = $request->startup_date;
                if ($request->hasFile('logo')) {
                    $randomNumber = mt_rand(1000000000, 9999999999);
                    $imagePath = $request->file('logo');
                    $imageName = $randomNumber . $imagePath->getClientOriginalName();
                    $imagePath->move('images/profile', $imageName);
                    $business->logo = $imageName;
                }
                $business->description = $request->description;
                $savedata = $business->save();
            }
            if ($savedata) {
                return response()->json(['status' => true, 'message' => "Business detail has been stored succesfully", 'data' => $savedata], 200);
            } else {
                return response()->json(['status' => false, 'message' => "There has been error for storing the business detail", 'data' => ""], 400);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
    public function document_upload(Request $request)
    {
        try {
            $user = User::where('id', $request->id)->first();
            if ($user) {
                $update = User::find($request->id);
                $update->proof1_no = $request->proof1_no;
                $update->proof2_no = $request->proof2_no;
                if ($request->hasFile('proof1_pic')) {
                    $randomNumber = mt_rand(1000000000, 9999999999);
                    $imagePath = $request->file('proof1_pic');
                    $imageName = $randomNumber . $imagePath->getClientOriginalName();
                    $imagePath->move('images/document', $imageName);
                    $update->proof1_pic = $imageName;
                }
                if ($request->hasFile('proof2_pic')) {
                    $randomNumber = mt_rand(1000000000, 9999999999);
                    $imagePath = $request->file('proof2_pic');
                    $imageName = $randomNumber . $imagePath->getClientOriginalName();
                    $imagePath->move('images/document', $imageName);
                    $update->proof2_pic = $imageName;
                }
                $savedata = $update->save();
            }
            if ($savedata) {
                return response()->json(['status' => true, 'message' => "Document  has been uploaded succesfully", 'data' => $savedata], 200);
            } else {
                return response()->json(['status' => false, 'message' => "There has been error for uploading the document", 'data' => ""], 400);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
    public function get_single_user(Request $request)
    {
        try {
            $user = User::where('id',$request->id)->first();
            if ($user) {
                return response()->json(['status' => true, 'message' => "single data fetching successfully", 'data' => $user], 200);
            } else {
                return response()->json(['status' => false, 'message' => "There has been error for fetching the single", 'data' => ""], 400);
            }
        } catch (\Exception $e) {
        }
    }
}
