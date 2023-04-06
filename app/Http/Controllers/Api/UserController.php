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

class UserController extends Controller
{
    public function userRegister(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|max:16',
                'role' => 'required|string'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 200);
            } else {
                // Store the user in the database
                $user = new User();
                $user->name = $request->firstname . " " . $request->lastname;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->role     = $request->role;
                $data = $user->save();


                $token = JWTAuth::fromUser($user);

                return response()->json(['status' => true, 'message' => 'User register successfully', 'data' => ['user' => $user, 'token' => $token]], 200);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

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

                $token = JWTAuth::fromUser($user);

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
                $business->save();
            }
            return response()->json(['status' => true, 'message' => 'User register successfully', 'data' => ['user' => $user, 'token' => $token]], 200);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }
    public function investor_register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8|max:16',
                'phone' => 'required|string|min:10|max:20',
                'gender' => 'required',
                'city' => 'required',
                'country' => 'required',
                'linkedinurl' => 'required|url',
                // 'profile_desc' => 'required|string'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 200);
            } else {
                // Store the user in the database
                $user = new User();
                $user->name = $request->firstname . " " . $request->lastname;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->phone = $request->phone;
                $user->gender = $request->gender;
                $user->city = $request->city;
                $user->country = $request->country;
                $user->linkedin_url = $request->linkedinurl;
                $user->profile = $request->profile;
                $user->residence_worth = $request->residence_worth;
                $user->experience = $request->experience;
                $data = $user->save();
                $otp = VerificationCode::create([
                    'user_id' => 1,
                    'otp' => rand(1000, 9999),
                    'expire_at' => Carbon::now()->addMinutes(1)
                ]);

                $token = JWTAuth::fromUser($user);

                return response()->json(['status' => true, 'message' => 'User register successfully', 'data' => ['user' => $user, 'token' => $token, 'otp' => $otp]], 200);
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
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }
            $user = Auth::user();
            return response()->json([
                'status' => 'User logged in successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
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
            $user = User::where('id', $request->id)->first();
            if ($user) {
                return response()->json(['status' => true, 'message' => "single data fetching successfully", 'data' => $user], 200);
            } else {
                return response()->json(['status' => false, 'message' => "There has been error for fetching the single", 'data' => ""], 400);
            }
        } catch (\Exception $e) {
        }
    }
    public function save_contact(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'subject' => 'required|string',
                'message' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            } else {
                // Store the user in the database
                $user = new Contact();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->subject = $request->subject;
                $user->message = $request->message;
                $user->save();

                $data = [
                    'name' => $user->name,
                    'email' => $user->email
                ];

                Mail::send('contactMail', ['data1' => $data], function ($message) use ($data) {
                    $message->from('demo93119@gmail.com', "StartUp");
                    $message->subject('Welcome to StartUp, ' . $data['name'] . '!');
                    $message->to($data['email']);
                });
                return response()->json(['status' => true, 'message' => 'Contact stored successfully', 'error' => '', 'data' => ''], 200);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function join_to_invest(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'invalid email error',
                    'errors' => $validator->errors(),
                ], 200);
            } else {
                return response()->json(['status' => true, 'message' => "valid email", 'data' => $request->all()], 200);
            }
        } catch (\Exception $e) {
        }
    }
    public function send_otp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid phone number',
                    'errors' => $validator->errors(),
                ], 200);
            }
            $user = User::find($request->id);
            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }
            $user->phone = $request->phone;
            $user->save();
            
            $otp = VerificationCode::where('user_id', $user->id)->first();
            if ($otp) {
                $otp->otp = rand(1000, 9999);
                $otp->expire_at = Carbon::now()->addMinutes(1);
                $otp->save();
            } else {
                $otp = VerificationCode::create([
                    'user_id' => $user->id,
                    'otp' => rand(1000, 9999),
                    'expire_at' => Carbon::now()->addMinutes(1),
                ]);
            }
            $data = [
                'name' => $user->name,
                'otp' => $otp->otp,
                'email' => $user->email
            ];
            Mail::send('otpMail', ['data' => $data], function ($message) use ($user) {
                $message->from('demo93119@gmail.com', "StartUp");
                $message->subject('Welcome to StartUp, ' . $user['name'] . '!');
                $message->to($user['email']);
            });
            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully',
                'data' => $otp->otp,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error sending OTP',
                'data' => $e->getMessage(),
            ], 400);
        }
    }

    public function confirm_otp(Request $request)
    {
        try {
            $verificationCode = VerificationCode::where('user_id', 1)->where('otp', $request->otp)->first();
            $now = Carbon::now();
            if (!$verificationCode) {
                return response()->json(['status' => false, 'message' => "Your OTP is not correct", 'data' => ''], 200);
            } elseif ($verificationCode && $now->isAfter($verificationCode->expire_at)) {
                return response()->json(['status' => false, 'message' => "Your OTP has been expired", 'data' => ''], 200);
            }

            return response()->json(['status' => true, 'message' => "otp successfully confirmed", 'data' => ''], 200);
        } catch (\Exception $e) {
        }
    }
}
