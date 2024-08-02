<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Api\Trait\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OmiseCharge;
use OmiseToken;

/**
 * omise 第三方支付
 */
class OmiseController extends Controller
{
    use ResponseTrait;

    /**
     * 信用卡支付
     *
     * @param Invoice $invoice
     * @param Request $request
     * @return array|void
     */
    public function card(Invoice $invoice, Request $request)
    {
        $data = $request->validate([
            'name'            => 'required',
            'number'          => 'required',
            'expirationMonth' => 'required',
            'expirationYear'  => 'required',
            'city'            => 'required',
            'postalCode'      => 'required',
            'securityCode'    => 'required',
        ]);


        define('OMISE_PUBLIC_KEY', 'pkey_test_60lpfhmbjbvguxhyhlz');
        define('OMISE_SECRET_KEY', 'skey_test_60lpfho6cexh3xxknos');
        define('OMISE_API_VERSION', '2019-05-29');

        $token = OmiseToken::create([
            'card' => [
                'name'             => $data['name'],
                'number'           => $data['number'],
                'expiration_month' => $data['expirationMonth'],
                'expiration_year'  => $data['expirationYear'],
                'city'             => $data['city'],
                'postal_code'      => $data['postalCode'],
                'security_code'    => $data['securityCode'],
            ]
        ]);

        $tokenStr = $token['id'];

        Log::info('omise-card-pay-info', $data);
        Log::info('omise-card-pay-token-id', $data);

        $charge = OmiseCharge::create([
            'amount'      => $invoice->price,
            'currency'    => 'JPY',
            'card'        => $tokenStr,
            'description' => 'invoice id:' . $invoice->id
        ]);

        $status = $charge['status'];

        if ($status != 'successful') {
            $failureMessage = $charge['failure_message'];
            return $this->apiError(message: $failureMessage);
        }

        DB::transaction(function () use ($invoice,$tokenStr, $request){
            $invoice->status = Invoice::STATUS_PAYED;
            $invoice->pay_at = date('Y-m-d H:i:s');
            $invoice->omise_token_id = $tokenStr;
            $invoice->saveOrFail();

            /**@var $student Student **/
            $student = $request->user();
            $student->courses()->attach($invoice->course_id);
        });

        return $this->apiSuccess();
    }
}
