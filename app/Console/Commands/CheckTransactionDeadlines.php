<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class CheckTransactionDeadlines extends Command
{
    protected $signature = 'check:transaction-deadlines';
    protected $description = 'Check for transactions that are 60 minutes before their deadline and send an email';

    public function handle()
    {
        // Fetch transactions that are ongoing and have a deadline in the future
        $transactions = Transaction::where('status', 'ongoing')
            ->where('deadline', '>', now()) 
            ->get();
            
        Log::info('Found ' . $transactions->count() . ' transactions to process.');

        // Fetch admin user only once
        $email_user = User::where('account_type', 'Admin')->first();

        // Check if there are transactions to process
        if ($transactions->isEmpty()) {
            Log::info('No ongoing transactions with upcoming deadlines found.');
            return;
        }

        // Check if an admin user exists
        if (!$email_user) {
            Log::warning('No admin user found to send a deadline reminder email.');
            return;
        }

        // Process each transaction
        foreach ($transactions as $transaction) {
            // Check if the transaction deadline is within the next 60 minutes
            $minutesToDeadline = now()->diffInMinutes($transaction->deadline, false);

            if ($minutesToDeadline <= 60 && $minutesToDeadline > 0) {
                try {
                    // Send the email
                    Mail::send('admin.deadline', ['transaction' => $transaction], function ($message) use ($email_user) {
                        $message->to($email_user->email);
                        $message->subject('Reminder: Transaction Deadline Approaching');
                    });

                    // Log success message
                    Log::info('Reminder email sent to ' . $email_user->email . ' for transaction ID ' . $transaction->id . '.');
                } catch (Exception $e) {
                    // Log if there's an error during the email sending process
                    Log::error('Failed to send email for transaction ID ' . $transaction->id . '. Error: ' . $e->getMessage());
                }
            }
        }

        // Log completion of the command
        Log::info('Transaction deadline check completed.');
    }
}
