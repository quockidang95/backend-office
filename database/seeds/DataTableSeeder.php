<?php

use Illuminate\Database\Seeder;

class DataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $limit = 10;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('order_items')->insert([
                'order_id' => rand(5, 15),
                'product_id' => rand(5, 15),
                'price' => rand(50000, 150000),
                'quantity' => rand(1, 5)
            ]);
        }
    }
}
