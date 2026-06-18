<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('comments')->insert([
            [
                'user_id' => '30',
                'item_id' => '1',
                'comment' => 'とても良い商品ですね！',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => '21',
                'item_id' => '1',
                'comment' => 'まだ在庫はありますか？',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => '13',
                'item_id' => '2',
                'comment' => '状態の詳細を教えてください。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
