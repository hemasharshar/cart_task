<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $tShirtId = \App\Models\Products::query()->create([
            'name' => 'T-shirt',
            'in_stock' => 20,
            'price' => 10.99,
        ]);

        \App\Models\Products::query()->create([
            'name' => 'Pants',
            'in_stock' => 20,
            'price' => 14.99,
        ]);

        $discountOffer = \App\Models\Offers::query()->create([
            'description' => '10% off',
            'discount' => 10,
        ]);

        \App\Models\Products::query()->create([
            'name' => 'Shoes',
            'in_stock' => 20,
            'price' => 24.99,
            'offer_id' => $discountOffer->id,
        ]);

        $sale = \App\Models\Offers::query()->create([
            'description' => 'Buy two t-shirts and get a jacket half its price',
            'discount' => 50,
            'quantity' => 2,
            'product_id' => $tShirtId->id,
        ]);

        \App\Models\Products::query()->create([
            'name' => 'Jacket',
            'in_stock' => 20,
            'price' => 19.99,
            'offer_id' => $sale->id,
        ]);
    }
}
