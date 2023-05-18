<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_code' => $faker->unique()->word(),
        'product_name' => $faker->state(),
        'image' => $faker->imageUrl(640, 480, 'food', true),
        'price' => $faker->randomNumber(5, true),
        'currency' => 'IDR',
        'discount' => $faker->numberBetween(0, 100),
        'dimensions' => $faker->numberBetween(0, 100). ' x '.$faker->numberBetween(0, 100),
        'unit' => 'Pcs',
    ];
});

$factory->state(Product::class, 'household', function ($faker){
    return [
        'image' => $faker->imageUrl(640, 480, 'household', true),
    ];
});

$factory->state(Product::class, 'dozen', [
    'unit' => 'Dozen',
]);

$factory->state(Product::class, 'bal', [
    'unit' => 'Bal',
]);