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
use App\Models\InvestorTerms;
use Mail;

class InvestorController extends Controller
{
    public function investor_type_information(Request $request)
    {
        try {
            $data = User::find($request->id);
            $data->investorType = $request->investorType;
            $data->reg_step_2 = '1';
            $data->is_profile_completed = '1';
            $data->save();

            //   $mail['email'] = $data->email;
            //   $mail['title'] = "Profile Completed";
            //   $mail['body'] =  "Profile has been Completed Successfully.";
            //   Mail::send('email.InvestorProfileCompleted', ['mail' => $mail], function ($message) use ($mail) {
            //       $message->to($mail['email'])->subject($mail['title']);
            //   });
            return response()->json([
                'status' => true,
                'message' => 'Profile has been Completed Successfully.',
                'data' => ['data' => $data]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error Occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function get_investor_type_information(Request $request)
    {
        try {
            $user = User::where('id', $request->id)->first();
            if ($user) {
                return response()->json(['status' => true, 'message' => "Data fetching successfully", 'data' => $user], 200);
            } else {
                return response()->json(['status' => false, 'message' => "There has been error for fetching the single", 'data' => ""], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error Occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function investor_angel_terms(Request $request)
    {
        try {
                $data = new InvestorTerms();
                $data->user_id = $request->user_id;
                $data->principal_residence = $request->principal_residence;
                $data->cofounder = $request->cofounder;
                $data->prev_investment_exp = $request->prev_investment_exp;
                $data->experience = $request->experience;
                $data->net_worth = $request->net_worth;
                $data->no_requirements = $request->no_requirements;
                $data->save();
                
            //   $mail['email'] = $data->email;
            //   $mail['title'] = "Profile Completed";
            //   $mail['body'] =  "Profile has been Completed Successfully.";
            //   Mail::send('email.InvestorProfileCompleted', ['mail' => $mail], function ($message) use ($mail) {
            //       $message->to($mail['email'])->subject($mail['title']);
            //   });
            return response()->json([
                'status' => true,
                'message' => 'Profile has been Completed Successfully.',
                'data' => ['data' => $data]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error Occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
