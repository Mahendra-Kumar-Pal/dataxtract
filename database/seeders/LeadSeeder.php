<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lead;
use Faker\Factory as Faker;

class LeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 20; $i++) {
            Lead::create([
                'date' => $faker->date('Y-m-d'),
                'name' => $faker->name,
                'mobile_no' => $faker->numerify('##########'), 
                'city' => $faker->city,
                'source' => $faker->randomElement(['Reference', 'Online', 'Advertisement']),
                'disposition' => $faker->randomElement(['Contactable', 'Not Contactable']),
                'lead_type' => $faker->randomElement(['Hot', 'Warm', 'Cold']),
                'attempted' => $faker->numberBetween(0, 5),
                'remark' => $faker->sentence,
            ]);
        }
    }
}
