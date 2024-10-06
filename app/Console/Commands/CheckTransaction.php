<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckTransaction extends Command
{
    protected $signature = 'check:transaction';
    protected $description = 'Check transaction deadlines';

    public function handle()
    {
        Log::info('CheckTransactionDeadlines command is running.');

        // Your logic for checking transaction deadlines goes here
        // e.g., checking the database for transactions close to their deadlines
        
        Log::info('Transaction deadlines checked successfully.');
    }
}
