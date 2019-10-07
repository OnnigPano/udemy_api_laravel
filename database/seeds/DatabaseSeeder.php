<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {   
        //SETEA LA DB PARA QUE NO HAYA PROBLEMA CON LAS FOREIGN KEYS AL BORRAR DATOS
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        //PARA QUE AL CREAR LA TOTALIDAD DE USUARIOS NO SE DISPAREN LOS EVENTOS
        //SE ENVIARIAN MILES DE MAILS AL CREAR LOS USUARIOS
        User::flushEventListeners();
        Product::flushEventListeners();
        Category::flushEventListeners();
        Transaction::flushEventListeners();


        //TRUNCATE ELIMINA LOS REGISTROS DE ESAS TABLAS PARA NO GENERAR PROBLEMAS AL VOLVER A EJECUTAR EL SEED
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $cantidadUsuarios = 1000;
        $cantidadCategorias = 30;
        $cantidadProductos = 1000;
        $cantidadTransacciones = 1000;

        factory(User::class, $cantidadUsuarios)->create();
        factory(Category::class, $cantidadCategorias)->create();

        /* 
            A CADA PRODUCTO SE LE VA A ASIGNAR UNA CANTIDAD RANDOM DE CATEGORIAS.
            PLUCK OBTIENE SOLO EL ID DE LA COLLECTION DE CATEGORIAS
            ATTACH RELACIONA CADA CATEGORIA A SU RESPECTIVO PRODUCTO.
        */
        factory(Product::class, $cantidadProductos)->create()->each(
            function($producto) {
                $categorias = Category::all()->random(rand(1,5))->pluck('id');

                $producto->categories()->attach($categorias);
            }
        );

        factory(Transaction::class, $cantidadTransacciones)->create();
    }
}
