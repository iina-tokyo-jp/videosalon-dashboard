<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\History;
use App\Models\Message;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

class MessagesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $histories = History::all();
        foreach ($histories as $history) {
            Message::create([
                'site_id' => 1,
                'status' => 0,
                'history_id' => $history->id,
                'body' => $faker->realText(),
                'read_flag' => 0,
                'user_id' => $history->user_id,
                'user_name' => $history->user_name,
                'user_account' => $history->user_account,
                'appraiser_id' => $history->appraiser_id,
                'appraiser_name' => $history->appraiser_name,
                'appraiser_account' => $history->appraiser_account,
                'add_date' => Carbon::now(),
                'pub_date' => Carbon::now()
            ]);
        }
    }
}
