<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Jobs\UpdateWalletJob;
use App\Models\Deposit;
use App\Models\Wallet;

class DepositController extends Controller
{
    public function __construct(){

    }

    public function store(Request $request){
        try {

            $deposit = Deposit::create([
                'order_id' => $request->order_id,
                'amount' => $request->amount
            ]);
            

            UpdateWalletJob::dispatch($deposit->amount, $request->user->username);
            return response()->json([
                'success'=>true,
                'data'=>$deposit,
                'message'=> 'deposit success'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'deposit failed'
            ], 500);
        }
    }

    public function withdrawl(Request $request){
        try {

            $wallet = Wallet::where('name',$request->user->username)->first();
            if(!$wallet){
                return response()->json([
                    'success' => false,
                    'message' => 'prohibitted'
                ], 403);
            }

            if($wallet->balance < $request->amount){
                return response()->json([
                    'success'=>false,
                    'data'=>['withdrawl_amount'=> $request->amount],
                    'message'=> 'wallet balance not enough'
                ], 422);
            }

            UpdateWalletJob::dispatch($request->amount*-1, $request->user->username);
            return response()->json([
                'success'=>true,
                'data'=>['withdrawl_amount'=> $request->amount],
                'message'=> 'withdraw success'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'withdraw failed'
            ], 500);
        }
    }
    
}
