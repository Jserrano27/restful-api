<?php

use App\Product;
use App\Transaction;
use App\User;
use App\Category;
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
        //Deshabilitamos llaves foraneas temporalmente para poder insertar los fatories
        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); 

        // antes de insertar, nos aseguramos que las tablas esten vacias, y para eso usamos truncate
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        //Deshabilitamos los event listeners para cuando corramos el seeder
        User::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();
        Category::flushEventListeners();
        

        //establecemos las cantidades de cada entidad
        $usersQuantity = 1000;
        $categoriesQuantity = 30;
        $productsQuantity = 1000;
        $transactionsQuantity = 1000;

        factory(User::class, $usersQuantity)->create();

        factory(Category::class, $categoriesQuantity)->create();

        //Ademas, para cada producto creado, le asignamos de una a cinco categorias de forma aleatoria
        factory(Product::class, $productsQuantity)->create()->each(
            function($product) {
                $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );

        factory(Transaction::class, $transactionsQuantity)->create();




    }
}
