<?php

use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->unique($reset = true)->safeEmail,
        'password' => bcrypt('secret'),
        'first_name' => $faker->firstName, 
        'last_name' => $faker->lastName,
    ];
});

$factory->define(App\Activation::class, function (Faker\Generator $faker) {
    return [
        'completed' => 1,
        'completed_at' => Carbon::now(),
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    $count_posts = App\Post::count();
    return [
        'post_id' => $faker->numberBetween($min = 1, $max = $count_posts),
        'name' => $faker->name,
        'message' => $faker->text($maxNbChars = 400),
        'is_published' => 1,
        'published_at' => $faker->dateTimeThisMonth($max = 'now'),
    ];
});

$factory->define(App\CommentReply::class, function (Faker\Generator $faker) {
    $count_comments = App\Comment::count();
    return [
        'comment_id' => $faker->numberBetween($min = 1, $max = $count_comments),
        'name' => $faker->name,
        'message' => $faker->text($maxNbChars = 400),
        'is_published' => 1,
        'published_at' => $faker->dateTimeThisMonth($max = 'now'),
    ];
});


$factory->define(App\Post::class, function(Faker\Generator $faker) {
    return [
        'title' => $faker->catchPhrase,
        'slug' => preg_replace("/[\s_]/", "-", strtolower($faker->catchPhrase)),
        'user_id' => 1,
        'is_published' => 1,
    ];
});