<?php

use Illuminate\Support\Facades\Artisan;
abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
    // Migrate database when start testing
    public function setUp()
    {
        parent::setUp();
        $this->refreshApplication();
        Artisan::call('migrate');
    }
    // Function to reset database
    public function reset(){
        Artisan::call('migrate:reset');
    }
}
