<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;

class PetSeeder extends Seeder
{
    public function run()
    {
        Pet::create([
            'name' => 'Milo',
            'species' => 'Dog',
            'dob' => '2008-10-06',
            'weight' => 12.5,
            'image' => 'https://placekitten.com/200/200'

        ]);
    }
}
