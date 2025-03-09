<?php

// app/Console/Commands/TestEmail.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test';
    protected $description = 'Send a test email';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Mail::raw('This is a test email', function ($message) {
            $message->to('recipient@example.com')
                    ->subject('Test Email');
        });

        $this->info('Test email sent successfully!');
    }
}
