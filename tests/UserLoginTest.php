<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserLoginTest extends TestCase
{
    public function testVisitLogin()
    {
        $this->visit('/');
        $this->click('Login');
        $this->seePageIs('/user/login');
    }

    public function testSeeLoginForm()
    {
      $this->visitRoute('/user/login');
      $this->see('Login');
      $this->see('Username');
      $this->see('Password');
      $this->see('Remember');
    }

    public function testLoginWrongCredentials()
    {
      $this->visit('/user/login');
      $this->type('wrong', 'username');
      $this->type('wrong', 'password');
      $this->press('Login');
      $this->see('Wrong user or pass.');
    }

    public function testSuccessfulLogin()
    {
      $this->visit('/user/login');
      $this->type('admin', 'username');
      $this->type('admin', 'password');
      $this->press('Login');
      $this->seePageIs('/');
      $this->see('AdminCP');
    }
}
