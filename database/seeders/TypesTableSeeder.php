<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全データ削除
        DB::table('types')->truncate();

        // 占い種類定義
        DB::table('types')->insert(['site_id' => 1, 'sort_no' =>  1, 'name' => 'オーラリーディング'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' =>  2, 'name' => '12星座占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' =>  3, 'name' => '易'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' =>  4, 'name' => '周易'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' =>  5, 'name' => '宿曜'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' =>  6, 'name' => '手相'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' =>  7, 'name' => '断易'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' =>  8, 'name' => '透視'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' =>  9, 'name' => '風水'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 10, 'name' => '千里眼'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 11, 'name' => '夢占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 12, 'name' => '数秘術'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 13, 'name' => '星占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 14, 'name' => 'スピリチュアル占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 15, 'name' => '梅花心易'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 16, 'name' => '水晶占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 17, 'name' => '紫微斗数'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 18, 'name' => '自動書記'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 19, 'name' => 'ルーン占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 20, 'name' => '宿曜占星術'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 21, 'name' => '手相占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 22, 'name' => '動物占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 23, 'name' => '印相占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 24, 'name' => '四柱推命'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 25, 'name' => '姓名判断'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 26, 'name' => '家相占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 27, 'name' => '星平会海'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 28, 'name' => '西洋占星術'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 29, 'name' => 'タロット占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 30, 'name' => 'チャネリング'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 31, 'name' => '算命学'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 32, 'name' => '0学'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 33, 'name' => '降霊術'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 34, 'name' => '前世占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 35, 'name' => '六壬神課'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 36, 'name' => '人相占い'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 37, 'name' => '九星気学'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 38, 'name' => '七政四餘'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 39, 'name' => 'タロット'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 40, 'name' => 'おみくじ'  ]);
        DB::table('types')->insert(['site_id' => 1, 'sort_no' => 41, 'name' => '西洋手相占い'  ]);

    }
}
