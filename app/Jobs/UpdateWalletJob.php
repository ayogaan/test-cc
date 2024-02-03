<?php

namespace App\Jobs;

use Exception; // Import the Exception class
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; // Import the Log facade
use App\Models\Wallet;

class UpdateWalletJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $amount;
    protected $username;
    public function __construct($amount, $username)
    {
        $this->amount = $amount;
        $this->username = $username;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $wallet = Wallet::where('name', $this->username)->first();
            if($wallet){
                    $wallet->update([
                        'balance'=> $wallet->balance+$this->amount
                    ]);
            }
            
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }
    }

}
