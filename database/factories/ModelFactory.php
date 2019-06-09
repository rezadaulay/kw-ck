<?php

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
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => $faker->sentence,
        'api_token' => Illuminate\Support\Str::random(60)
    ];
});

$factory->define(App\ChecklistTemplate::class, function (Faker\Generator $faker) {
    return [
        'name' => $this->faker->sentence,
        'checklist' => $this->faker->paragraph,
        'items' => $this->faker->paragraph
    ];
});

$factory->define(App\Checklist::class, function (Faker\Generator $faker) {
    return [
        'object_domain' => $this->faker->sentence,
        'object_id' => $this->faker->sentence,
        'description' => $this->faker->paragraph,
        'urgency' => 1,
        'is_completed' => 0
        // 'is_completed' => $this->faker->paragraph
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    // $checklist = factory(App\Checklist::class)->create();
    return [
        // 'checklist_id' => $checklist->id,
        'description' => $this->faker->sentence,
        'due' => date('Y-m-d H:i:s'),
        'assignee_id' => rand(1, 2),
        'urgency' => 1,
        'is_completed' => 0
    ];
});
