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
use App\Mail\EmailVerification;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DB;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordReset;

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
                'role' => 'required|string',
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
                $user->name = $request->firstname . " " . $request->lastname;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->role     = $request->role;
                $data = $user->save();
                $token = Str::random(40);
                $domain = env('NEXT_URL_LOGIN');
                $url = $domain . '/?token=' . $token;
                $mail['url'] = $url;
                $mail['email'] = $request->email;
                $mail['title'] = "Verify Your Account";
                $mail['body'] = "Please click on below link to verify your Account";
                $user->where('id', $user->id)->update(['email_verification_token' => $token, 'email_verified_at' => Carbon::now()]);

                Mail::send('email.emailVerify', ['mail' => $mail], function ($message) use ($mail) {
                    $message->to($mail['email'])->subject($mail['title']);
                });
                $token = JWTAuth::fromUser($user);

                return response()->json(['status' => true, 'message' => 'Verification link has been sent to your email.', 'data' => ['user' => $user, 'token' => $token]], 200);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
            return response()->json(['success' => true, 'msg' => 'User has not been Register Successfully.'], 500);
        }
    }

    public function user_login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            } else {
                $credentials = $request->only('email', 'password');
                $token = JWTAuth::attempt($credentials);
                if (!$token) {
                    return response()->json([
                        'status' => false,
                        'error' => '',
                        'message' => 'Invalid Email and Password',
                    ], 200);
                }
                $user = Auth::user();
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'user' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
            }
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
            return response()->json(['success' => true, 'msg' => 'User is not authorized to log in successfully.'], 500);
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

    public function reset_password(Request $request)
    {

        try {
            $user =  User::where('email', $request->email)->first();
            if ($user) {
                $token = Str::random(40);
                $domain = env('NEXT_URL');
                $url = $domain . '/?userid=' . $user->id . '&resettoken=' . $token;
                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = "password reset";
                $data['body'] = "Please click on below link to reset your password";
                Mail::send('email.ResetPasswordMail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });
                $datetime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'user_id' => $user->id,
                        'token' => $token,
                        'created_at' => $datetime,
                    ]
                );
                return response()->json(['status' => true, 'message' => 'Mail has been sent please check your email!'], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Mail doesn`t not exist'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 200);
        }
    }
    public function check_user_reset_password_verfication(Request $request)
    {
        $resetData = PasswordReset::where('user_id', $request->id)->where('token', $request->token)->count();

        if ($resetData > 0) {
            return response()->json(['status' => true,]);
        } else {
            return response()->json(['status' => false, 'message' => 'Invalid User Authoriztation']);
        }
    }

    public function updated_reset_password(Request $request)

    {
        try {
            $request->validate([
                'password' => 'required|string|min:8',
            ]);
            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['status' => false, 'msg' => 'User not found'], 200);
            }
            $user->password = Hash::make($request->password);
            $user->new_password = $request->password;
            $user->update();
            PasswordReset::where('user_id', $request->user_id)->delete();
            return response()->json(['status' => true, 'message' => 'Password reset successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'message' => 'Password reset failed'], 500);
        }
    }

    public function check_user_email_verfication(Request $request)
    {
        try {

            $id = $request->id;
            $token = $request->token;

            $check = UserVerify::where('user_id', $id)->where('token', $token)->count();

            if ($check > 0) {

                $user = User::where('id', $id)->update([

                    'email_verified' => 1,
                    'email_verified_at' => Carbon::now(),
                ]);

                UserVerify::where('user_id', $id)->where('token', $token)->delete();

                return response()->json(['message' => "Email verifiy successfully", 'status' => true], 200);
            } else {

                return response()->json(['message' => "Email verfication failed", 'status' => false], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => true, 'msg' => 'Password reset failed'], 200);
        }
    }
}
