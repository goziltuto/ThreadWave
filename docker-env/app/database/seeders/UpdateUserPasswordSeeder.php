<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UpdateUserPasswordSeeder extends Seeder
{
    public function run()
    {
        // 特定のユーザーのパスワードを更新
        DB::table('users')
            ->where('email', 'kanri@gmail.com')
            ->update([
                'password' => Hash::make('root'),
                'updated_at' => Carbon::now(),
            ]);
    }
}
