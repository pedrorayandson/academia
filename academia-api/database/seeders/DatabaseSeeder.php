<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aluno;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            Aluno::create([
                'nome' => $faker->name,
                'idade' => $faker->numberBetween(18, 60),
                'peso' => $faker->randomFloat(2, 50, 100),
                'altura' => $faker->randomFloat(2, 150, 200),
                'pago' => $faker->boolean,
            ]);
        }
    }
}
