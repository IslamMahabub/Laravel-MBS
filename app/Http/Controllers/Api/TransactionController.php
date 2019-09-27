<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Account;
use App\Transaction;
use App\Transfer;
use Illuminate\Http\Request;
use Validator;

class TransactionController extends Controller
{

    public function index(Request $request)
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

        $accountData 	= Account::where('account_no', $request->account_no)->first();
        if(!$accountData || $accountData->user_id != auth()->user()->id){
        	$response = [
                'success' => false,
                'message' => 'Invalid Account No'
            ];

            return response()->json($response, 404);
        }

		$transaction 	= Transaction::where('account_id', $accountData->id)->get();

		return response()->json([
                'success' => true,
	            'data'    => $transaction,
	            'message' => 'Transaction history.'
            ], 200);
    }
     
    public function depost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_no'            => 'required',
            'amount'            	=> 'required',
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

        $accountData 	= Account::where('account_no', $request->account_no)->first();
        if(!$accountData || $accountData->user_id != auth()->user()->id){
        	$response = [
                'success' => false,
                'message' => 'Invalid Account No'
            ];

            return response()->json($response, 404);
        }
        $account 		= auth()->user()->accounts()->find($accountData->id);
        
        $trasaction = Transaction::create([
            'account_id'            => $accountData->id,
            'transaction_type'  	=> 'D',
            'amount'              	=> $request->amount,
            'transaction_action'    => 'D',
            'transaction_date'      => date('Y-m-d')
        ]);
        if($trasaction){
        	$account->current_balance = $accountData->current_balance + $request->amount;
        	if ($account->save())
            return response()->json([
                'success' => true,
	            'data'    => array(
	            	'Account No'	=> $request->account_no,
	            	'Amount' 		=> $request->amount
	            ),
	            'message' => 'Depost amount successfully.'
            ], 200);
        }

    }

    public function withdraw(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_no'            => 'required',
            'amount'            	=> 'required',
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

        $accountData 	= Account::where('account_no', $request->account_no)->first();
        if(!$accountData || $accountData->user_id != auth()->user()->id){
        	$response = [
                'success' => false,
                'message' => 'Invalid Account No'
            ];

            return response()->json($response, 404);
        }
        $account 		= auth()->user()->accounts()->find($accountData->id);
        
        $trasaction = Transaction::create([
            'account_id'            => $accountData->id,
            'transaction_type'  	=> 'W',
            'amount'              	=> $request->amount,
            'transaction_action'    => 'C',
            'transaction_date'      => date('Y-m-d')
        ]);
        if($trasaction){
        	$account->current_balance = $accountData->current_balance - $request->amount;
        	if ($account->save())
            return response()->json([
                'success' => true,
	            'data'    => array(
	            	'Account No'	=> $request->account_no,
	            	'Amount' 		=> $request->amount
	            ),
	            'message' => 'Withdraw amount successfully.'
            ], 200);
        }

    }

    public function transfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_no'            => 'required',
            'transfer_account_no'   => 'required',
            'amount'            	=> 'required',
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

        $accountData 	= Account::where('account_no', $request->account_no)->first();
        if(!$accountData || $accountData->user_id != auth()->user()->id){
        	$response = [
                'success' => false,
                'message' => 'Invalid Account No'
            ];

            return response()->json($response, 404);
        }
        $account 		= auth()->user()->accounts()->find($accountData->id);

        $transferaccountData 	= Account::where('account_no', $request->transfer_account_no)->first();
        $transferaccount 		= auth()->user()->accounts()->find($transferaccountData->id);
        

        $transfer = Transfer::create([
            'from_account'            	=> $accountData->id,
            'to_account'  				=> $transferaccountData->id,
            'remark'              		=> $request->remark,
        ]);

        $trasaction = Transaction::create([
            'account_id'            => $accountData->id,
            'transfer_id'           => $transfer->id,
            'transaction_type'  	=> 'T',
            'amount'              	=> $request->amount,
            'transaction_action'    => 'C',
            'transaction_date'      => date('Y-m-d')
        ]);

        $trasaction = Transaction::create([
            'account_id'            => $transferaccountData->id,
            'transfer_id'           => $transfer->id,
            'transaction_type'  	=> 'D',
            'amount'              	=> $request->amount,
            'transaction_action'    => 'D',
            'transaction_date'      => date('Y-m-d')
        ]);

        if(true){
        	$account->current_balance 			= $accountData->current_balance - $request->amount;
        	$account->save();
        	$transferaccount->current_balance 	= $transferaccountData->current_balance + $request->amount;
        	$transferaccount->save();
            return response()->json([
                'success' => true,
	            'data'    => array(
	            	'From'		=> $request->account_no,
	            	'To'		=> $request->transfer_account_no,
	            	'Amount' 	=> $request->amount
	            ),
	            'message' => 'Transfer amount successfully.'
            ], 200);
        }

    }


    public function transferHistory(Request $request)
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

        $accountData 	= Account::where('account_no', $request->account_no)->first();
        if(!$accountData || $accountData->user_id != auth()->user()->id){
        	$response = [
                'success' => false,
                'message' => 'Invalid Account No'
            ];

            return response()->json($response, 404);
        }
        $account 		= auth()->user()->accounts()->find($accountData->id);
        
        $transaction 	= Transaction::where('account_id', $accountData->id)->get();

		return response()->json([
                'success' => true,
	            'data'    => $transaction,
	            'message' => 'Transaction history.'
            ], 200);

    }

}
