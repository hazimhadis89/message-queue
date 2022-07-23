<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Message;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $datetime = now()->addSecond($i)->toIso8601String();
            $message = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus feugiat odio ac enim vulputate, at fermentum felis consectetur. Suspendisse nec est tempus nam.';
            Message::store($message, $datetime);
        }
    }
}
