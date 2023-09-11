<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全データ削除
        DB::table('sites')->truncate();

        // IDリセット
        DB::table('sites_id_seq')->select("setval ('sites_id_seq', 1, false)");

        DB::table('sites')->insert([
            'credential' => 'bqtric314x57r3f5rdz6',
            'status' => 1,
            'name' => 'テストサイト１',
            'profile' => 'テストサイトのプロフィール',
            'company_id' => 1
        ]);
    }
}
