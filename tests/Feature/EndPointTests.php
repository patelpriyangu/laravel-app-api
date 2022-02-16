<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\{Models\LoanPaymentOrder, Models\LoanApplication, Models\User};
use Tests\TestCase;


class EndPointTests extends TestCase
{

    const API_LOAN = '/api/loan-applications/';
    const API_USER = '/api/login/';
    const API_REGISTER = '/api/register';
    const API_LOAN_REPAYMENT = '/api/repayment/pay';


    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected function authenticate()
    {
        $user = User::firstOrNew([
            'name' => 'test',
            'email'=> 'feature_test@gmail.com',
            'password' => \Hash::make('secret1234'),
        ]);

        if (!auth()->attempt(['email'=>$user->email, 'password'=>'secret1234'])) {
            return response(['message' => 'Login credentials are invaild']);
        }

        return auth()->attempt(['email'=>$user->email, 'password'=>'secret1234']);
    }

    protected function admin_authenticate(){
        $user = User::firstOrNew([
            'name' => 'admin',
            'email'=> 'admin@test.com',
            'password' => \Hash::make('password'),
        ]);

        if (!auth()->attempt(['email'=>$user->email, 'password'=>'password'])) {
            return response(['message' => 'Login credentials are invaild']);
        }

        return auth()->attempt(['email'=>$user->email, 'password'=>'password']);
    }


    public function testRegister()
    {
        $response = $this->json('POST', SELF::API_REGISTER, [
            'name'  =>  $name = 'Test',
            'email'  =>  $email = rand(12345,678910).'test_register@example.com',
            'password'  =>  $password = '123456789',
        ]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);

    }


    public function testLogin(){
        $response = $this->json('POST', '/api/login', [
            'email'  =>  $email = 'priyangu@test.com',
            'password'  =>  $password = 'password',
        ]);

        //Write the response in laravel.log
        \Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);

        // Receive our token
        $this->assertArrayHasKey('access_token',$response->json());
    }


    public function testLoanCreateSuccess()
    {
        global $loan_id;
        $token = $this->authenticate();        
        $newLoan = [
            'loan_amount'          => 5000,
            'loan_term'            => 12,
            'description'       => 'This is a sample description for loan',
        ];

        $response = $this->json('post', self::API_LOAN, $newLoan, [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                'data' => [ 'id',
                            'loan_amount',
                            'loan_term',
                            'weekly_payment_amount',
                            'paid_amount_total',
                            'repayment_total',
                            'description',
                            'status',
                        ]
                 ]);
        if(isset($response->json()['data']['id'])){
            $loan_id = $response->json()['data']['id'];
        }
    }

    public function testLoanCreateRequiresAll()
    {
        $token    = $this->authenticate();   
        
        $response = $this->json('post', self::API_LOAN, [], [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(422)
                 ->assertJson([
                    'errors' => [
                        'loan_amount'          => ['The loan amount field is required.'],
                        'loan_term' => ['The loan term field is required.'],
                    ],
                 ]);
    }

    public function testLoanGet()
    {
        global $loan_id;
        $token    = $this->authenticate();   
        $response = $this->json('get', self::API_LOAN, [], [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                'data'=>[ 0=>[
                            'id',
                            'loan_amount',
                            'loan_term',
                            'weekly_payment_amount',
                            'paid_amount_total',
                            'repayment_total',
                            'description',
                            'status'
                        ],
                    ]
                 ]);
    }

    public function testLoanGetByUser()
    {
        global $loan_id;
        $token    = $this->authenticate();   
        $response = $this->json('get', self::API_LOAN .$loan_id, [], [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data'=>[
                        'id',
                        'loan_amount',
                        'loan_term',
                        'weekly_payment_amount',
                        'paid_amount_total',
                        'repayment_total',
                        'description',
                        'status'
                        ]
                 ]);
    }


    public function testLoanApproveStatus()
    {
        global $loan_id;
        $token    = $this->admin_authenticate();   
        $updateloan = [
            'loan_amount'          => 5000,
            'loan_term'            => 12,
            'status'               => 'approved',
        ];
        $response = $this->putJson(self::API_LOAN .$loan_id, $updateloan, [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data'=>[
                        'id',
                        'loan_amount',
                        'loan_term',
                        'weekly_payment_amount',
                        'paid_amount_total',
                        'repayment_total',
                        'description',
                        'status'
                        ]
                 ]);
    }

    public function testLoanRepayment()
    {
        global $loan_id;
        $token    = $this->authenticate();   
        $updateloan['loan_application_id'] = $loan_id;
        $response = $this->postJson(self::API_LOAN_REPAYMENT,$updateloan, [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'data'=>[
                        'user_id',
                        'loan_application_id',
                        'amount',
                        'status',
                        'updated_at',
                        'created_at',
                        'id' 
                        ]
                 ]);
    }

    public function testLoanDelete()
    {
        global $loan_id;
        $token    = $this->authenticate();   
        $response = $this->deleteJson(self::API_LOAN .$loan_id, [], [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }
}
