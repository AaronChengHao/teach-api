<?php

namespace Tests\Feature;

use App\Models\Invoice;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $account = 'phpunit-test-1';
        $password = '123456';
        $response = $this->post('/api/login', ['type' => 'teacher','username' => $account, 'password' => $password]);
        $resJson =  $response->json();
        $accessToken = $resJson['data']['token']['access_token'];
        $this->withToken($accessToken);
    }

    /**
     * 学生登录
     *
     * @return void
     */
    public function useStudentToken()
    {
        $account = 'phpunit-test-1';
        $password = '123456';
        $response = $this->post('/api/login', ['type' => 'student','username' => $account, 'password' => $password]);
        $resJson =  $response->json();
        $accessToken = $resJson['data']['token']['access_token'];
        $this->withToken($accessToken);
    }

    /**
     * 创建账单测试
     */
    public function test_create(): void
    {
        $studentId = 9; // 测试学生id
        $teacherId = 14; // 测试老师id

        // 删除测试创建的账单
        Invoice::whereStudentId($studentId)->whereTeacherId($teacherId)->delete();

        // 创建账单
        $yearMonth = '2012-12';
        $response = $this->post('/api/t/invoices',[
            "year_month" => $yearMonth,
            "student_id" => $studentId
        ]);
        $resJson =  $response->json();
        $this->assertTrue($resJson['code'] === 0);
    }

    /**
     * 账单发送
     */
    public function test_send(): void
    {
        $studentId = 9; // 测试学生id
        $teacherId = 14; // 测试老师id
        $invoices = Invoice::whereStudentId($studentId)->whereTeacherId($teacherId)->whereStatus(Invoice::STATUS_WAIT_SEND)->get();
        foreach ($invoices as $invoice) {
            $response = $this->post("/api/t/invoices/{$invoice->id}/send",[]);
            $resJson =  $response->json();
            $this->assertTrue($resJson['code'] === 0);
        }
    }

    /**
     * 支付账单测试
     */
    public function test_pay(): void
    {

        $account = 'phpunit-test-1';
        $password = '123456';
        $response = $this->post('/api/login', ['type' => 'teacher','username' => $account, 'password' => $password]);
        $resJson =  $response->json();
        $accessToken = $resJson['data']['token']['access_token'];
        $this->withToken($accessToken);

        $studentId = 9; // 测试学生id
        $teacherId = 14; // 测试老师id
        $invoices = Invoice::whereStudentId($studentId)->whereTeacherId($teacherId)->whereStatus(Invoice::STATUS_WAIT_PAY)->get();

        $this->useStudentToken();
        foreach ($invoices as $invoice) {
            // 创建账单
            $response = $this->post("/api/s/invoices/{$invoice->id}/card-pay",[
                "name" => "aaron",
                "number" => "4242424242424242",
                "expirationMonth" => "12",
                "expirationYear" =>  "2026",
                "city" => "ChengDu",
                "postalCode" => "123",
                "securityCode" => "123"
            ]);
            $resJson =  $response->json();
            $this->assertTrue($resJson['code'] === 0);
        }
    }
}
