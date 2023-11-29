<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provider::create([
            'name' => 'EDTEAM',
            'ruc'=> '094375234',
            'phone' => '945786485'
        ]);
        Provider::create([
            'name' => 'ADIDAS',
            'ruc'=> '349857456',
            'phone' => '965094560'
        ]);
        Provider::create([
            'name' => 'RADEON S.A',
            'ruc'=> '094375234',
            'phone' => '941231243'
        ]);
        Provider::create([
            'name' => 'FIRE TECH',
            'ruc'=> '23467T4556',
            'phone' => '943534985'
        ]);
    }
}
