<?php

use Illuminate\Database\Seeder;

use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name'  => 'Notebook Dell XPS',
            'price' => '8750.74',
            'weight'    => '0.785',
            'user_id'   => '1'
        ]);

        Product::create([
            'name'  => 'Bicicleta Rockrider BIG RR 8',
            'price' => '5280.35',
            'weight'    => '7.241',
            'user_id'   => '1'
        ]);

        Product::create([
            'name'  => 'Video Game Playstation 4',
            'price' => '2199.99',
            'weight'    => '3.457',
            'user_id'   => '1'
        ]);
    }
}