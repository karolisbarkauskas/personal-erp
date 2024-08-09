<?php

namespace App\Console\Commands;

use App\Client;
use App\ClientContacts;
use App\Income;
use App\Label;
use App\Mail\DebtorEmail;
use App\Sms\Sms;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class InformDebtors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inform:debtors {--dry-run : Dry run the command}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sms = new Sms();
        $dry = $this->option('dry-run');
        $income = Income::withoutGlobalScopes()->Depts()->get()->groupBy('client');
        $debtors = ["Skolininkų sąrašas: \n"];
        $totalDebt = 0;

        foreach ($income as $client => $collection) {
            /** @var Client $clientObject */
            $clientObject = Client::find($client);

            $this->output->text($clientObject->name);
            $debtors[] = $clientObject->name;
            $debt = $collection->sum(function (Income $item) {
                return $item->getDept();
            });
            $smsNeeded = false;
            $collection->map(function (Income $item) use (&$smsNeeded) {
                $smsNeeded = $item->getPaymentOverdueDays() >= 15;
            });

            $totalDebt += $debt;

            if ($clientObject->contacts->isEmpty()) {
                continue;
            }

            if ($dry) {
                $mail = Mail::to('karolis@barkauskas.net');
            } else {
                $mail = Mail::cc('karolis@barkauskas.net');
                $debt = Label::formatPrice(abs($debt));
                $message = "invoyer: \nSveiki, negavome $debt mokėjimo už Jums išrašytas sąskaitas.\nSąskaitą(as) rasite el. pašte.\nLauksime mokėjimo,\nGražios dienos!";
                /** @var ClientContacts $contact */
                foreach ($clientObject->contacts as $contact) {
                    $mail->to($contact->email);
                    if ($contact->phone && $smsNeeded) {
                        $sms->sendSms($contact->phone, 'invoyer', $message);
                    }
                }
            }

            try {
                $mail->send(new DebtorEmail($clientObject, $dry));
            } catch (\Exception $exception) {
                $this->output->error($exception->getMessage());
            }
        }
        if ($dry && count($debtors) > 0) {
            $debtors[] = "Viso skolų suma: " . Label::formatPrice(abs($totalDebt)) . "\n";
            $sms->sendSms('37066611211', 'invoyer', implode("\n", $debtors));
        }
    }
}
