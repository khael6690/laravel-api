<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run()
    {
        // Buat 10 user dummy terlebih dahulu
        User::factory(10)->create();

        // Non-aktifkan event listener untuk performa
        Item::flushEventListeners();

        // Buat 500 item dummy
        Item::factory(500)->create();

        // Contoh penggunaan state khusus
        Item::factory(5)->longDescription()->create();
        Item::factory(3)->recentlyCreated()->create();
    }
}
