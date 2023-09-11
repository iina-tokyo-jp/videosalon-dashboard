<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全データ削除
        DB::table('companies')->truncate();

        // IDリセット
        DB::table('companies')->select("setval ('companies_id_seq', 1, false)");

        DB::table('companies')->insert([
            'name' => 'イイナその１',
            'address' => '東京都渋谷区',
            'profile' => 'テストプロフィール',
            'division' => '',
            'person' => '担当者ダミー'
        ]);

        DB::table('companies')->insert([
            'name' => 'イイナその２',
            'address' => '神奈川県鎌倉市',
            'profile' => '会社プロフィール',
            'division' => '',
            'person' => ''
        ]);
    }
}
