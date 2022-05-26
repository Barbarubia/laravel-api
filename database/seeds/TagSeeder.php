<?php

use Illuminate\Database\Seeder;
use App\Tag;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // Genero 10 tag usando il faker
        for ($i=0; $i < 10; $i++) {
            $tag = $faker->word();
            Tag::create([
                'name'  => $tag,
                'slug'  => Str::slug($tag)
            ]);
        }
    }
}
