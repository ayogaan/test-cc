<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Jobs\UpdateWalletJob;
use App\Models\Deposit;
use App\Models\Wallet;
use Firebase\JWT\JWT;
use Firebase\JWT\KEY;

class Home extends Component
{

    public $amount;
    public $wd;
    public $balance;
    public $apikey;
   
    public function render()
    {
        return view('livewire.home');
    }

    public function updateWallet(){
        $secretKey = 'your_secret_key';
        $decoded = JWT::decode($this->apikey, new Key($secretKey, 'HS256'));
        $wallet = Wallet::where('name',$decoded->username)->first();
        $this->balance = $wallet->balance;
    }
    
    public function store(){
        try {

            $deposit = Deposit::create([
                'order_id' => round(microtime(true) * 1000),
                'amount' => $this->amount
            ]);
            $secretKey = 'your_secret_key';
            $decoded = JWT::decode($this->apikey, new Key($secretKey, 'HS256'));

            UpdateWalletJob::dispatch($deposit->amount, $decoded->username);
            $this->amount = 0;
        } catch (\Exception $e) {
            dd($e);
        
        }
    }

    public function withdrawl(){
        try {
            $secretKey = 'your_secret_key';
            $decoded = JWT::decode($this->apikey, new Key($secretKey, 'HS256'));
            $wallet = Wallet::where('name',$decoded->username)->first();
            if(!$wallet){
                return response()->json([
                    'success' => false,
                    'message' => 'prohibitted'
                ], 403);
            }

            if($wallet->balance < $this->wd){
                
            }

            UpdateWalletJob::dispatch($this->wd*-1, $decoded->username);
            

        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function submitForm()
    {
        $client = new Client();

        try {
            $response = $client->post('http://localhost:8000/api/deposit', [
                'headers' => [
                    'Authorization' => $this->apikey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'amount' => $this->amount,
                    'order_id' => '11111',
                    // Add other form fields as needed
                ],
            ]);

            // Handle the response as needed

            // Optionally, you can redirect or emit events after successful submission
        } catch (RequestException $e) {
            // Handle the exception
            // You can log the error, display a message to the user, or take other actions
            // For example, you can use the following line to log the error:
            // \Log::error('HTTP Request Error: ' . $e->getMessage());
            dd($e);
            // Example of displaying an error message to the user
            $this->addError('submitForm', 'Error submitting the form. Please try again later.');
        }
    }
}
