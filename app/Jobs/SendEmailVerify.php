<?php

namespace App\Jobs;

use App\Http\Controllers\VerifyController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailVerify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    public $tries = 3; // so failad method will repeat three time

    /**
     * Create a new job instance.
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = $this->email;

        // Recreate a new Request object using the email
        $request = new Request(['email' => $email]);

        // Now you can call the method with the recreated Request
        $verify = new VerifyController();
        $verify->sendEmailVerificationCode($request);
    }

    public function failed(\Exception $exception)
    {
        info("Job failed: " . $exception->getMessage()); // this will store in storage/logs/laravel.log
        $this->release(5);

    }
}
