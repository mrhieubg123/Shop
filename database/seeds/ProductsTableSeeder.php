<?php
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Product;
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        for ($i = 0; $i < 30; $i++) {
            for ($j = 1; $j <= 10; $j++) {
                $product = new Product();
                $product->name = $faker->text(15);
                $product->price = $faker->randomNumber(5);
                $product->description = $faker->text();
                $product->quantity = $faker->randomNumber(3);
                $product->category_id = $j;
                $product->brand_id = $faker->numberBetween(1, 7);
                $product->save();
            }
        }
    }
}