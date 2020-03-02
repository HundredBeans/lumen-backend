<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

/**
 * This test was used to Test the API. The way i approach this test was to make it step by step, so it can populate the database while doing testing (because i didnt use seed). After testing, i include the reset function from TestCase to reset the database so it is ready to do another test immidiately
 *
 * 
 */
class ApiRouteTest extends TestCase
{
    /**
     * Test login admin
     */
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
    /**
     * Generate token for admin authentication to be used for next test
     */
    public function getTokenAdmin(){
        $loginData = array(
            "username"=>"admin",
            "password"=>"admin123"
        );

        $json = $this->json('POST', '/login', $loginData)->response->getContent();;
        $response = json_decode($json);
        return $response->token;
    }
    /**
     * Test add new CD for Admin
     */
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
    /**
     * Test Edit CD for Admin
     */
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
    /**
     * Test register new user
     */
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
    /**
     * Test login user
     */
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
    /**
     * Function to generate token for User to be used in the next test
     */
    public function getTokenUser(){
        $loginData = array(
            "username"=>"testdata",
            "password"=>"rahasia"
        );
        $json = $this->json('POST', '/login', $loginData)->response->getContent();;
        $response = json_decode($json);
        return $response->token;
    }
    /**
     * Test get cd list for User
     */
    public function testGetCdList(){
        $token = $this -> getTokenUser();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->get('/cd', $headers);
        $this->assertResponseStatus(200);
    }
    /**
     * Test get specific CD by ID for user
     */
    public function testGetCdByID(){
        $token = $this -> getTokenUser();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->get('cd/1', $headers);
        $this->assertResponseStatus(200);
    }
    /**
     * Test rent a CD by ID for User
     */
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
    /**
     * Test see rented CD for User
     */
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
    /**
     * Test return CD for User
     */
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
    }
    /**
     * Test Admin delete CD by ID
     */
    public function testDeleteCdAdmin(){
        $token = $this->getTokenAdmin();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );

        $this->delete('cd/1', [], $headers);
        $this->assertResponseStatus(200);
        $this->seeJsonContains([
            'success' => true,
            'message' => 'Delete CD success'
            ]);
    }
    /**
     * Test Admin get list of Returned CD
     */
    public function testGetReturnedCdAdmin(){
        $token = $this->getTokenAdmin();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->get('/rent/returned', $headers);
        $this->assertResponseStatus(200);
        $this->seeJsonContains([
            'total_rent' => 1,
        ]);
        // To Reset database after all test
        $this->reset();
    }
    /**
     * Test Admin get list of Returned CD
     */
    public function testGetNotReturnedCdAdmin(){
        $token = $this->getTokenAdmin();
        $headers = array(
            "Authorization"=>"Bearer $token"
        );
        $this->get('/rent/notreturned', $headers);
        $this->assertResponseStatus(200);
        $this->seeJsonContains([
            'total_rent' => 0,
        ]);
        // To Reset database after all test
        $this->reset();
    }
}
