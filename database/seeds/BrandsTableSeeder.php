<?php
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class BrandsTableSeeder extends Seeder
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
        for ($i=0; $i < 7; $i++) {
            Brand::create([
                'name'=>$faker->name,
            ]);
        }
    }
}