<?php

namespace App\Jobs;

use App\Mail\StartConversation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class StartChatNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            foreach($this->data['receiver'] AS $mail){

                $mailInfo = [
                    'sender'=>$this->data['sender'],
                    'message'=>$this->data['message'],
                    'receiver_name'=>$mail['name'],
                    'receiver_email'=>$mail['email'],
                ];
                Mail::to($mail['email'])->send(new StartConversation($mailInfo));
                Log::info('Job assigning email sent successfully.', ['data' => $mailInfo]);
            }
        } catch (\Exception $exception) {
            Log::error('Failed to send job assigning email.', ['exception' => $exception->getMessage(), 'data' => $mailInfo]);
        }
    }
}
