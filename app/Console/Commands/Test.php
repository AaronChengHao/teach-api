<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        define('OMISE_PUBLIC_KEY', 'pkey_test_60lpfhmbjbvguxhyhlz');
        define('OMISE_SECRET_KEY', 'skey_test_60lpfho6cexh3xxknos');
        define('OMISE_API_VERSION', '2019-05-29');

        $token = \OmiseToken::create([
            'card' => [
                'name' => 'Zin Kyaw Kyaw',
                'number' => '4242 4242 4242 4242',
                'expiration_month' => 12,
                'expiration_year' => date('Y', strtotime('+2 years')),
                'city' => 'Bangksok',
                'postal_code' => '10320',
                'security_code' => 123
            ]
        ]);
        $token = $token['id'];


        // 直接收费
        $charge = \OmiseCharge::create(array('amount'      => '100',
                                             'currency'    => 'JPY',
                                             'card'        => $token,
                                             'description' => 'Got it from try-omise-php repository in Github'));


        var_dump($charge);die;

        $customer = \OmiseCustomer::create([
            'email' => 'zinkyawkyaw@example.com',
            'description' => 'Zin Kyaw Kyaw',
            'card' =>$token
        ]);

        var_dump($customer->toArray());die;

        // Charge a card.
        $charge = \OmiseCharge::create(array('amount'      => '1',
                                            'currency'    => 'thb',
//                                            'card'        => '4242424242424242',
                                            'description' => 'Got it from try-omise-php repository in Github'));

        var_dump($charge);die;
    }
}
