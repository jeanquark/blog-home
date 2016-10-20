<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    protected $toTruncate = ['users', 'roles', 'role_users', 'posts', 'tags', 'post_tags', 'comments', 'comment_replies'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        foreach($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }

        $this->call(UsersTableSeeder::class);

        $this->call(PostsTableSeeder::class);

        Model::reguard();
    }
}
