<?php

use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\User;

class CompleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert users
        factory(User::class, 1)->states('admin')->create();
        factory(User::class, 6)->create();

        // Insert foods
        factory(Product::class, 6)->create();
        factory(Product::class, 6)->states('dozen')->create();
        factory(Product::class, 6)->states('bal')->create();

        // Insert households
        factory(Product::class, 6)->states('household')->create();
        factory(Product::class, 6)->states('household', 'dozen')->create();
        factory(Product::class, 6)->states('household', 'bal')->create();
    }
}
