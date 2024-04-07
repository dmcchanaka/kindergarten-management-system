<?php

namespace App\Jobs;

use App\Mail\NewsfeedAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNewsFeedNotification implements ShouldQueue
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
            foreach($this->data AS $mail){
                $mailInfo = [
                    'receiver_name'=>$mail['parent_name'],
                    'receiver_email'=>$mail['parent_email'],
                ];
                Mail::to($mail['parent_email'])->send(new NewsfeedAlert($mailInfo));
                Log::info('newsfeed email sent successfully.', ['data' => $mailInfo]);
            }
        } catch (\Exception $exception) {
            Log::error('Failed to send job assigning email.', ['exception' => $exception->getMessage(), 'data' => $mailInfo]);
        }
    }
}
