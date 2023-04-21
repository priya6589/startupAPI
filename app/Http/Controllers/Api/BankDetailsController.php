<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class BankDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function bank_details(Request $request)
    {
        try {
            //  $data= $request->all();
            $data                 = new BankDetails();
            $data->user_id        = $request->id;
            $data->bank_name      = $request->bank_name;
            $data->account_holder = $request->account_holder;
            $data->account_no     = $request->account_no;
            $data->ifsc_code       = $request->ifsc_code;
            $data->save();
            $user=User::where('id',$request->id)->update(['reg_step_4'=>'1','is_profile_completed'=>'1']);
            return response()->json(['status' => true, 'message' => 'Bank Details stored successfully', 'data' => ['bank_details' => $data]], 200);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_bank_information(Request $request)
    {
        try {
            $userId = $request->id;
            $data  = BankDetails::where('user_id', $userId)->first();
            if ($data) {
                $data  = BankDetails::where('user_id', $userId)->first();
                    return response()->json(['status' => true, 'message' => "Data fetching successfully", 'data' => $data], 200);
                }
        } catch (\Exception $e) {
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
