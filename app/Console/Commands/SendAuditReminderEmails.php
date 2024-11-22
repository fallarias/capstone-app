<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Audit;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class SendAuditReminderEmails extends Command
{
    protected $signature = 'audit:send-reminders';

    protected $description = 'Send email reminders for upcoming audit deadlines';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Log the start of the email reminder process
        //Log::info('Starting to send audit reminder emails.');
    
        // Fetch all audits that are not finished
        $audits = Audit::whereNull('finished')->get();
    
        // Check if there are any audits
        if ($audits->isEmpty()) {
            Log::info('No ongoing transactions with upcoming deadlines found.');
            return;
        }
    
        // Loop through each audit
        foreach ($audits as $audit) {
            // Check if an email reminder has already been sent for this audit
            if ($audit->email_reminder_sent) {
                Log::info('Already sent an email for transaction ID ' . $audit->audit_id . '.');
                continue; // Skip to the next audit
            }
    
            // Fetch the admin user based on department and account type
            $email_user = User::where('department', $audit->office_name)
                ->where('account_type', 'office staff')
                ->first();
    
            // Check if an admin user exists
            if (!$email_user) {
                Log::warning('No admin user found to send a deadline reminder email for transaction ID ' . $audit->id . '.');
                continue; // Continue processing the other transactions
            }
    
            // Check if the transaction deadline is within the next 60 minutes
            $minutesToDeadline = Carbon::now()->diffInMinutes($audit->deadline, false);
    
            if ($minutesToDeadline <= 55 && $minutesToDeadline > 0) {
                try {
                    // Send the email
                    Mail::send('admin.deadline', ['transaction' => $audit], function ($message) use ($email_user) {
                        $message->to($email_user->email);
                        $message->subject('Reminder: Transaction Deadline Approaching');
                    });
    
                    // Mark as reminder sent
                    $audit->email_reminder_sent = true;
                    $audit->save();
    
                    // Log success message
                    Log::info('Reminder email sent to ' . $email_user->email . ' for transaction ID ' . $audit->id . '.');
                } catch (Exception $e) {
                    // Log if there's an error during the email sending process
                    Log::error('Failed to send email for transaction ID ' . $audit->id . '. Error: ' . $e->getMessage());
                }
            }
        }
    }
    
    
}
