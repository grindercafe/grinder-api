<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;

class CheckPaymentStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check if payment still pending';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payments = Payment::where('status', 'pending')
        ->where('created_at', '<', now()->subMinutes(10)->toDateString())->get();

        foreach ($payments as $payment) {
            $payment->booking->tables()->detach();
            $payment->update(['status'=> 'timeout']);
        }

        $this->info('successful detach');
    }
}
