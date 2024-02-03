<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Firebase\JWT\JWT;

class WalletController extends Controller
{

    public function store(Request $request){
        try{
            $wallet = Wallet::create([
                'name' => $request->name,
                'balance' => 0
            ]);

            $secretKey = 'your_secret_key';

            $expirationTime = time() + (10 * 365 * 24 * 60 * 60);

            $payload = [
                'user_id' => 123,
                'username' => $request->name,
                'exp' => $expirationTime,
            ];
            $token = JWT::encode($payload, $secretKey, 'HS256');
            return response()->json([
                'success' => false,
                'data' => ['name'=> $wallet->name, 'token'=>$token],
                'message' => 'Wallet created'
            ], 200);

        }catch(Exception $err){
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'failed create wallet'
            ], 500);
        }

    }

    public function getById(Request $request){
        try {

            $wallet = Wallet::where('name',$request->user->username)->first();
            return response()->json([
                'success'=>true,
                'data'=>$wallet,
                'message'=> 'get my wallet'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'get wallet failed'
            ], 500);
        }
    }
    
}
