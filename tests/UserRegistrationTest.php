<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class UserRegistrationTest extends TestCase
{
    protected function setUp()
    {
      parent::setUp();
      $this->faker        = app('Faker\Generator');
    }

    public function testVisitRegistrationPage()
    {
      $this->visit('user/register');
      $this->seePageIs('/user/register');
      $this->see('register')->see('username')->see('email')->see('password');
    }

    public function testRequiredFields()
    {
      $this->visit('/user/register');
    }

    public function testRegistration()
    {
      $username     = $this->faker->username;
      $email        = $this->faker->email;
      $password     = Hash::make('123456');

      $this->visit('/user/register');
      $this->type($username, 'username');
      $this->type($email, 'email');
      $this->type($password, 'password');
      $this->press('Register');

      // $storedUserPass = User::where('username', $username)->first()->password;
      // $this->assertTrue(is_object($storedUserPass));
      //
      // $this->seeInDatabase('users', [
      //   'username' => $this->username,
      //   'password' => Hash::check($this->password, $storedUserPass)
      // ]);
    }

}
