<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Seller;
use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {

    //Traigo vendedores (usuarios que ya tengan productos a la venta).
    $vendedor = Seller::has('products')->get()->random();
    //Traigo a usuarios que no sean el mismo vendedor anterior
    $comprador = User::all()->except($vendedor->id)->random();

    return [
        'quantity' => $faker->numberBetween(1, 3),
        'buyer_id' => $comprador->id,
        'product_id' => $vendedor->products->random()->id
    ];
});
