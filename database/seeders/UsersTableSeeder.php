<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全データ削除
        DB::table('users')->truncate();

        // IDリセット
        DB::table('users')->select("setval ('users_id_seq', 1, false)");

        // 一般会員
        DB::table('users')->insert([
            'state'  => 1,
            'site_id' => 1,
            'actor_id' => 1,
            'login_id' => 'user1@fortune-star.info',
            'login_pw' => 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2'
        ]);
        DB::table('users')->insert([
            'state'  => 1,
            'site_id' => 1,
            'actor_id' => 1,
            'login_id' => 'user2@fortune-star.info',
            'login_pw' => 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2'
        ]);
        DB::table('users')->insert([
            'state'  => 1,
            'site_id' => 1,
            'actor_id' => 1,
            'login_id' => 'user3@fortune-star.info',
            'login_pw' => 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2'
        ]);
        DB::table('users')->insert([
            'state'  => 1,
            'site_id' => 1,
            'actor_id' => 1,
            'login_id' => 'user4@fortune-star.info',
            'login_pw' => 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2'
        ]);

        // 占い師
        DB::table('users')->insert([
            'state'  => 1,
            'site_id' => 1,
            'actor_id' => 2,
            'login_id' => 'ft1@fortune-star.info',
            'login_pw' => 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2'
        ]);
        DB::table('users')->insert([
            'state'  => 1,
            'site_id' => 1,
            'actor_id' => 2,
            'login_id' => 'ft2@fortune-star.info',
            'login_pw' => 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2'
        ]);
        DB::table('users')->insert([
            'state'  => 1,
            'site_id' => 1,
            'actor_id' => 2,
            'login_id' => 'ft3@fortune-star.info',
            'login_pw' => 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2'
        ]);
        DB::table('users')->insert([
            'state'  => 1,
            'site_id' => 1,
            'actor_id' => 2,
            'login_id' => 'ft4@fortune-star.info',
            'login_pw' => 'c3ab8ff13720e8ad9047dd39466b3c8974e592c2fa383d4a3960714caef0c4f2'
        ]);
    }
}
