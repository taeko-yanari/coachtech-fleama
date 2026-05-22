<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\User;


class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = User::pluck('id');

        $items = [
            [
                'user_id' => $userIds->random(),
                'name' => '腕時計',
                'price' => 15000,
                'brand_name'  => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => '良好',
                'status' => 'selling',
                'categories' => ['メンズ','アクセサリー'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds->random(),
                'name' => 'HDD',
                'price' => 5000,
                'brand_name'  => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => '目立った傷や汚れなし',
                'status' => 'sold',
                'categories' => ['家電'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds->random(),
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand_name'  => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition' => 'やや傷や汚れあり',
                'status' => 'selling',
                'categories' => ['インテリア','ハンドメイド'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds->random(),
                'name' => '革靴',
                'price' => 4000,
                'brand_name'  => null,
                'description' => 'クラシックなデザインの革靴',
                'condition' => '状態が悪い',
                'status' => 'sold',
                'categories' => ['ファッション','メンズ'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds->random(),
                'name' => 'ノートPC',
                'price' => 45000,
                'brand_name'  => null,
                'description' => '高性能なノートパソコン',
                'condition' => '良好',
                'status' => 'selling',
                'categories' => ['家電'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds->random(),
                'name' => 'マイク',
                'price' => 8000,
                'brand_name'  => null,
                'description' => '高音質のレコーディング用マイク',
                'condition' => '目立った傷や汚れなし',
                'status' => 'sold',
                'categories' => ['家電'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds->random(),
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name'  => null,
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
                'status' => 'selling',
                'categories' => ['ファッション','レディース'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds->random(),
                'name' => 'タンブラー',
                'price' => 500,
                'brand_name'  => null,
                'description' => '使いやすいタンブラー',
                'condition' => '状態が悪い',
                'status' => 'selling',
                'categories' => ['メンズ','アクセサリー'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds->random(),
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand_name'  => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'condition' => '良好',
                'status' => 'selling',
                'categories' => ['キッチン'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds->random(),
                'name' => 'メイクセット',
                'price' => 2500,
                'brand_name'  => null,
                'description' => '便利なメイクアップセット',
                'condition' => '目立った傷や汚れなし',
                'status' => 'selling',
                'categories' => ['ファッション','レディース'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $categoryMap = [
            'ファッション' => 1,
            '家電' => 2,
            'インテリア' => 3,
            'レディース' => 4,
            'メンズ' => 5,
            'コスメ' => 6,
            '本' => 7,
            'ゲーム' => 8,
            'スポーツ' => 9,
            'キッチン' => 10,
            'ハンドメイド' => 11,
            'アクセサリー' => 12,
            'おもちゃ' => 13,
            'ベビー・キッズ' => 14,
        ];

        foreach ($items as $data) {
            $item = Item::create([
                'user_id' => $data['user_id'],
                'name' => $data['name'],
                'price' => $data['price'],
                'brand_name' => $data['brand_name'],
                'description' => $data['description'],
                'condition' => $data['condition'],
                'status' => $data['status'],
            ]);

            $categoryId = array_map(fn($c) => $categoryMap[$c], $data['categories']);
            $item->categories()->attach($categoryId);
        }
    }
}
