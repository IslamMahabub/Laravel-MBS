<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Account;
use Illuminate\Http\Request;
use Validator;

class AccountController extends Controller
{
    public function index()
    {
        $account = auth()->user()->accounts;
 
        return response()->json([
            'success' 	=> true,
            'data' 		=> $account
        ], 200);
    }
 
    public function show($id)
    {
        $account = auth()->user()->accounts()->find($id);
 
        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Account with id ' . $id . ' not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $account->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_no'    => 'required|unique:accounts'
        ]);


        if($validator->fails()){

            $response = [
                'success' => false,
                'message' => 'Validation Error.'
            ];
            if(!empty($validator->errors())){
                $response['data'] = $validator->errors();
            }

            return response()->json($response, 404);
        }

 
        $account = Account::create([
            'user_id'               => auth()->user()->id,
            'account_no'            => $request->account_no,
            'account_opening_date'  => date('Y-m-d'),
            'currency'              => ($request->currency) ? $request->currency : 'Euro',
            'current_balance'       => 0
        ]);
        $response = [
            'success' => true,
            'data'    => array(
            ),
            'message' => 'Account create successfully.'
        ];

        return response()->json($response, 200);
    }
 
    public function update($id, Request $request)
    {
        $account = auth()->user()->accounts()->find($id);

        $validator = Validator::make($request->all(), [
            'account_no'            => 'required',
        ]);


        if($validator->fails()){

            $response = [
                'success' => false,
                'message' => 'Validation Error.'
            ];
            if(!empty($validator->errors())){
                $response['data'] = $validator->errors();
            }

            return response()->json($response, 404);
        }
 
        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Account with id ' . $id . ' not found'
            ], 400);
        }

        $account->account_no    = $request->account_no;
        $account->currency      = $request->currency;
 
        if ($account->save())
            return response()->json([
                'success' => true,
                'message' => 'Account updated seccefully'
            ], 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Account could not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $account = auth()->user()->accounts()->find($id);
 
        if (!$account) {
            return response()->json([
                'success' => false,
                'message' => 'Account with id ' . $id . ' not found'
            ], 400);
        }
 
        if ($account->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Account deleted seccefully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Account could not be deleted'
            ], 500);
        }
    }

    public function getBalance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_no'            => 'required',
        ]);

        if($validator->fails()){

            $response = [
                'success' => false,
                'message' => 'Validation Error.'
            ];
            if(!empty($validator->errors())){
                $response['data'] = $validator->errors();
            }

            return response()->json($response, 404);
        }

        $accountData = Account::where('account_no', $request->account_no)->first();
        
        if (!$accountData) {
            return response()->json([
                'success' => false,
                'message' => 'Account No: ' . $request->account_no . ' not found'
            ], 400);
        }
 
        return response()->json([
                'success' => true,
                'data' => ['Account NO' => $request->account_no, 'Current Balance' => $accountData->current_balance, 'Currency' => $accountData->currency],
            ], 200);
    }
}
