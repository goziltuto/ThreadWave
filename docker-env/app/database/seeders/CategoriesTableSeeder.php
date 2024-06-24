<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // カテゴリーデータの配列を定義
        $categories = [
            ['name' => '日常・生活'],
            ['name' => 'アニメ・マンガ'],
            ['name' => '学習・教育'],
            ['name' => '映画・テレビ'],
            ['name' => '音楽・演劇'],
            ['name' => 'ゲーム・ホビー'],
            ['name' => 'ホラー・心霊'],
            ['name' => '料理・食品'],
            ['name' => '旅行・アウトドア'],
            ['name' => '美容・ファッション'],
            ['name' => '健康・フィットネス'],
            ['name' => '科学・技術'],
            ['name' => 'ビジネス・経済'],
            ['name' => '趣味・娯楽'],
            ['name' => 'スポーツ・フィットネス'],
        ];

        // カテゴリーデータをデータベースに挿入
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
