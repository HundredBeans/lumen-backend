<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class ApiRouteTest extends TestCase
{
    // use DatabaseMigrations;
    // use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    // Test login admin
    public function testLoginAdmin()
    {
        $data = array(
            "username"=>"admin",
            "password"=>"admin123"
        );

        $this->post('/login', $data);
        $this->seeStatusCode(200);
        $this->seeJsonContains([
            'success' => true, 
            'message' => 'Logged in as admin success',
        ]);
    }
    // Get Token Admin
    public function getTokenAdmin(){
        $loginData = array(
            "username"=>"admin",
            "password"=>"admin123"
        );

        $json = $this->json('POST', '/login', $loginData)->response->getContent();;
        $response = json_decode($json);
        return $response->token;
    }
    // Test Admin add new CD
    public function testAddNewCd(){
        $data = array(
            "title"=>"Judul Film",
            "rate"=>3000,
            "category"=>"Kategori Film",
            "quantity"=>10
        );
        $token = $this->getTokenAdmin();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->post('/add', $data, $headers);
        $this->seeStatusCode(201);
        $this->seeJsonContains([
            'success' => true, 
            'message' => 'Add new CD success',
        ]);
    }
    // Test Admin add quantity CD
    public function testEditCd(){
        $data = array(
            "quantity"=>99
        );
        $token = $this->getTokenAdmin();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->put('/cd/1', $data, $headers);
        $this->seeStatusCode(201);
        $this->seeJsonContains([
            'success' => true, 
            'message' => 'Edit existing CD success',
        ]);
    }
    // Test register new user
    public function testRegisterUser()
    {
        // Register User
        $data = array(
            "name"=>"Test Data",
            "username"=>"testdata",
            "password"=>"rahasia"
        );

        $this->post('/register', $data);
        $this->seeStatusCode(201);
        $this->seeJsonContains([
            'success' => true, 
            'message' => 'Register Success',
        ]);
    }
    // Test login User
    public function testLoginUser(){
        $loginData = array(
            "username"=>"testdata",
            "password"=>"rahasia"
        );
    
        // Login User
        $this->post('/login', $loginData);
        $this->seeStatusCode(200);
        $this->seeJsonContains([
            'success' => true, 
            'message' => 'Login Success',
        ]);

    }
    // Get token user
    public function getTokenUser(){
        $loginData = array(
            "username"=>"testdata",
            "password"=>"rahasia"
        );
        $json = $this->json('POST', '/login', $loginData)->response->getContent();;
        $response = json_decode($json);
        return $response->token;
    }
    // User see CD List
    public function testGetCdList(){
        $token = $this -> getTokenUser();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->get('/cd', $headers);
        $this->assertResponseStatus(200);
    }
    // User see Specific CD
    public function testGetCdByID(){
        $token = $this -> getTokenUser();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->get('cd/1', $headers);
        $this->assertResponseStatus(200);
    }
    // User rent a CD
    public function testRentCd(){
        $token = $this -> getTokenUser();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->post('cd/1/rent', [], $headers);
        $this->assertResponseStatus(200);
        $this->seeJsonContains([
            'success' => true,
            'message' => 'rent success',
            'borrower' => "Test Data",
        ]);
    }
    // User see their rented CD
    public function testSeeRentCd(){
        $token = $this -> getTokenUser();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->get('user/rent', $headers);
        $this->assertResponseStatus(200);
        $this->seeJsonContains([
            'borrower' => 'Test Data',
            'total_rent'=>1,
        ]);
    }
    // User return their rented CD
    public function testReturnRentCd(){
        $token = $this -> getTokenUser();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->post('user/return/1',[], $headers);
        $this->assertResponseStatus(200);
        $this->seeJsonContains([
            'success'=>true,
            'message'=>'Return CD success',
            'borrower' => 'Test Data',
            ]);
        // To Reset database after all test
        $this->reset();
    }
}
