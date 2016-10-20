<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    // Some basic tests

    use DatabaseTransactions;


    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Page Heading')
             ->click('Contact')
             ->seePageIs('/contact');
    }


    public function testPagesAvailability()
    {

        $response1 = $this->call('GET', 'about');
        $response2 = $this->call('GET', 'services');
        $response3 = $this->call('GET', 'contact');

        $this->assertEquals(200, $response1->status());
        $this->assertEquals(200, $response2->status());
        $this->assertEquals(200, $response3->status());

    }

    public function testUserLogin()
    {
        $user = factory(App\User::class)->create();

        DB::table('activations')->insert([
            'user_id' => $user->id,
            'completed' => 1,
        ]);
        DB::table('role_users')->insert([
            'user_id' => $user->id,
            'role_id' => 3,
        ]);

        $this->visit('/login')
             ->type($user->email, 'email')
             ->type('secret', 'password')
             ->press('Sign In')
             ->see('Welcome');
    }


    public function testModeratorLogin()
    {
        $user = factory(App\User::class)->create();

        DB::table('activations')->insert([
            'user_id' => $user->id,
            'completed' => 1,
        ]);
        DB::table('role_users')->insert([
            'user_id' => $user->id,
            'role_id' => 2,
        ]);

        $this->visit('/login')
             ->type($user->email, 'email')
             ->type('secret', 'password')
             ->press('Sign In')
             ->see('Manage Posts')
             ->dontSee('Manage Users');
    }


    public function testAdminLogin()
    {
        $user = factory(App\User::class)->create();

        DB::table('activations')->insert([
            'user_id' => $user->id,
            'completed' => 1,
        ]);
        DB::table('role_users')->insert([
            'user_id' => $user->id,
            'role_id' => 1,
        ]);

        $this->visit('/login')
             ->type($user->email, 'email')
             ->type('secret', 'password')
             ->press('Sign In')
             ->see('Manage Users');
    }

}