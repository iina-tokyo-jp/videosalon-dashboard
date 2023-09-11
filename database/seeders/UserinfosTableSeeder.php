<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserinfosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全データ削除
        DB::table('userinfos')->truncate();

        // IDリセット
        DB::table('userinfos')->select("setval ('userinfos_id_seq', 1, false)");

        // 一般会員
        DB::table('userinfos')->insert([
            'site_id' => 1,
            'user_id' => 1,
            'name' => '池田　陽子',
            'birth_year' => 1987,
            'birth_month' => 1,
            'birth_day' => 12,
            'email' => 'user1@fortune-star.info ',
            'phoneno' => '',
            'gender' => 1,
            'bloodtype' => '',
            'point' => 5000,
        ]);
        DB::table('userinfos')->insert([
            'site_id' => 1,
            'user_id' => 2,
            'name' => '磯村　陽子',
            'birth_year' => 1968,
            'birth_month' => 12,
            'birth_day' => 2,
            'email' => 'user2@fortune-star.info ',
            'phoneno' => '',
            'gender' => 1,
            'bloodtype' => '',
            'point' => 7500,
        ]);
        DB::table('userinfos')->insert([
            'site_id' => 1,
            'user_id' => 3,
            'name' => '結城　かりん',
            'birth_year' => 1995,
            'birth_month' => 7,
            'birth_day' => 21,
            'email' => 'user3@fortune-star.info ',
            'phoneno' => '',
            'gender' => 1,
            'bloodtype' => '',
            'point' => 2500,
        ]);
        DB::table('userinfos')->insert([
            'site_id' => 1,
            'user_id' => 4,
            'name' => '土方　只子',
            'birth_year' => 1986,
            'birth_month' => 4,
            'birth_day' => 24,
            'email' => 'user4@fortune-star.info ',
            'phoneno' => '',
            'gender' => 1,
            'bloodtype' => '',
            'point' => 10000,
        ]);

        // 占い師
        DB::table('userinfos')->insert([
            'site_id' => 1,
            'user_id' => 5,
            'name' => '１占い師デモ',
            'birth_year' => 2000,
            'birth_month' => 1,
            'birth_day' => 1,
            'email' => 'ft1@fortune-star.info ',
            'phoneno' => '',
            'gender' => 1,
            'bloodtype' => '',
            'point' => 100000,
        ]);
        DB::table('userinfos')->insert([
            'site_id' => 1,
            'user_id' => 6,
            'name' => '２占い師デモ',
            'birth_year' => 2000,
            'birth_month' => 1,
            'birth_day' => 1,
            'email' => 'ft1@fortune-star.info ',
            'phoneno' => '',
            'gender' => 1,
            'bloodtype' => '',
            'point' => 0,
        ]);
        DB::table('userinfos')->insert([
            'site_id' => 1,
            'user_id' => 7,
            'name' => '３占い師デモ',
            'birth_year' => 2000,
            'birth_month' => 1,
            'birth_day' => 1,
            'email' => 'ft3@fortune-star.info ',
            'phoneno' => '',
            'gender' => 1,
            'bloodtype' => '',
            'point' => 0,
        ]);
        DB::table('userinfos')->insert([
            'site_id' => 1,
            'user_id' => 8,
            'name' => '４占い師デモ',
            'birth_year' => 2000,
            'birth_month' => 1,
            'birth_day' => 1,
            'email' => 'ft4@fortune-star.info ',
            'phoneno' => '',
            'gender' => 1,
            'bloodtype' => '',
            'point' => 0,
        ]);

    }
}
