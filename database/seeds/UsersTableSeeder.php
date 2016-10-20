<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        factory(App\User::class, 3)->create()->each(function($u) {
            $u->activation()->save(factory(App\Activation::class)->make());
        });

        $users = App\User::all();
        $count = count($users);

        $slug = ['admin', 'mod', 'user'];
        $name = ['Administrator', 'Moderator', 'User'];
        $permission = ['{"user.create:1, user.update:1, user.delete:1, user.view:1"}', '{"user.create:1, user.update:1, user.view:1"}', '{"user.view:1"}'];

        for ($i = 0; $i < $count; $i++) {

            DB::table('roles')->insert([
                'slug' => $slug[$i],
                'name' => $name[$i],
                'permissions' => $permission[$i],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('role_users')->insert([
                'user_id' => $users[$i]->id,
                'role_id' => $users[$i]->id,
                'created_at' => Carbon::now(),      
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
