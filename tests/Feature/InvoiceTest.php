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
     * 创建账单测试
     */
    public function test_create(): void
    {
        // 创建课程
//        $courseName = '自动化测试专用课程';
//        $response = $this->post('/api/t/courses',[
//            "name" => $courseName,
//            "price" => "1234.5",
//            "year_month" => "2012-12"
//        ]);
//
//        $resJson =  $response->json();
//        $this->assertTrue($resJson['code'] === 0);

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
     * 支付账单测试
     */
    public function test_pay(): void
    {
        $studentId = 9; // 测试学生id
        $teacherId = 14; // 测试老师id
        $invoices = Invoice::whereStudentId($studentId)->whereTeacherId($teacherId)->whereStatus(Invoice::STATUS_WAIT_PAY)->all();
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
