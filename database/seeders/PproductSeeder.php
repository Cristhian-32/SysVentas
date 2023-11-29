<?php

namespace Database\Seeders;

use App\Models\Pproduct;
use Illuminate\Database\Seeder;

class PproductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pproduct::create([
            'name'        => 'LARAVEL LIVEWIRE',
            'cost'        => 200,
            'price'       => 350,
            'barcode'     => '750100655987',
            'stock'       => 100,
            'alerts'      => 10,
            'category_id' => 1,
            'provider_id' => 1,
            'image'       => 'curso.png'
        ]);
        Pproduct::create([
            'name'        => 'RUNNING NIKE',
            'cost'        => 600,
            'price'       => 1500,
            'barcode'     => '750100655014',
            'stock'       => 100,
            'alerts'      => 10,
            'category_id' => 2,
            'provider_id' => 2,
            'image'       => 'tenis.png'
        ]);
        Pproduct::create([
            'name'        => 'IPHONE 11',
            'cost'        => 900,
            'price'       => 1400,
            'barcode'     => '750100655541',
            'stock'       => 100,
            'alerts'      => 10,
            'category_id' => 3,
            'provider_id' => 3,
            'image'       => 'iphone11.png'
        ]);
        Pproduct::create([
            'name'        => 'PC GAMER',
            'cost'        => 790,
            'price'       => 1350,
            'barcode'     => '750100655812',
            'stock'       => 100,
            'alerts'      => 10,
            'category_id' => 4,
            'provider_id' => 4,
            'image'       => 'pcgamer.png'
        ]);
    }
}
