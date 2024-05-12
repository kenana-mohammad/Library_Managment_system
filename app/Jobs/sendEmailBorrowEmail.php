<?php

namespace App\Jobs;

use App\Mail\BorrowBook;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class sendEmailBorrowEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user_id;
    public $book;

    public function __construct($book)
    {
        //
        $this->book=$book;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $data=[
            'email'=>'admin@gmail.com',
        ];
        // foreach($data as $datas)
        // {
            Mail::to($data)->send(new BorrowBook());
        // }
    }
}
