<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DateTime;
use App\Exchange_rates;

class HourlyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hour:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency exchange rates';

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
     * @return mixed
     */
    public function handle()
    {
        
        // ** КОНВЕРТАТОР ВАЛЮТЫ **//
        $cacheDir = '/tmp/bnm'; // not required
        $client = new \Fruitware\Bnm\Client($cacheDir);

        $rates = $client->get(new DateTime());

        // Конверт. 1 USD в MDL
        $usd_to_mdl = $rates->exchange('USD', 1, 'MDL');

        // Конверт. 1 EUR в MDL
        $eur_to_mdl = $rates->exchange('EUR', 1, 'MDL');
        //** КОНЕЦ КОНВЕРТАТОРА **/

        $update_currency = new Exchange_rates();
        $update_currency->usd = $usd_to_mdl;
        $update_currency->eur = $eur_to_mdl;
        $update_currency->save();

        $this->info('Currency exchange rates updated successfully');
    }
}
