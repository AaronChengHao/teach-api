<?php

namespace Tests\Feature;

use Tests\TestCase;

class InvoiceTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        $account = 'teacher1';
        $password = '111111';
        $response = $this->post('/api/login', ['type' => 'teacher','username' => $account, 'password' => $password]);
        $resJson =  $response->json();
        $this->assertTrue($resJson['code'] === 0);

        $accessToken = $resJson['data']['token']['access_token'];
        $this->withToken($accessToken);

        // 创建课程
        $courseName = 'test-漫画中国';
        $response = $this->post('/api/t/courses',[
            "name" => $courseName,
            "price" => "1122.1",
            "year_month" => "2022-12"
        ]);

        $resJson =  $response->json();
        $this->assertTrue($resJson['code'] === 0);

        // 创建账单
        $studentId =1;
        $response = $this->post('/api/t/invoices',[
            "year_month" => "2022-12",
            "student_id" => $studentId
        ]);

        $resJson =  $response->json();
        $this->assertTrue($resJson['code'] === 0);



    }
}
