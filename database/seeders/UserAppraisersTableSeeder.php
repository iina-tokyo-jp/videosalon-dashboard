<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserAppraisersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全データ削除
        DB::table('user_appraisers')->truncate();

        // IDリセット
        DB::table('user_appraisers')->select("setval ('user_appraisers_id_seq', 1, false)");


        DB::table('user_appraisers')->insert([
            'site_id' => 1,
            'user_id' => 5,
            'appraiser_id' => 1,
        ]);
        DB::table('user_appraisers')->insert([
            'site_id' => 1,
            'user_id' => 6,
            'appraiser_id' => 2,
        ]);
        DB::table('user_appraisers')->insert([
            'site_id' => 1,
            'user_id' => 7,
            'appraiser_id' => 3,
        ]);
        DB::table('user_appraisers')->insert([
            'site_id' => 1,
            'user_id' => 8,
            'appraiser_id' => 4,
        ]);
    }
}
