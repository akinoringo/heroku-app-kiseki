<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	DB::table('users')->insert([
            [
                'id' => 1,
        		'name' => 'akinoringo',
        		'email' => 'test@akinori.com',
        		'password' => Hash::make('akinoringo')
            ],[
                'id' => 2,
                'name' => 'ゲストユーザー',
                'email' => 'guest@akinori.com',
                'password' => Hash::make('akinoringo')
            ],[
                'id' => 3,
                'name' => '世良真澄',
                'email' => 'guest2@akinori.com',
                'password' => Hash::make('akinoringo')                
            ],[
                'id' => 4,
                'name' => '本堂英介',
                'email' => 'guest3@akinori.com',
                'password' => Hash::make('akinoringo')
            ],[
                'id' => 5,
                'name' => '大岡紅葉',
                'email' => 'guest4@akinori.com',
                'password' => Hash::make('akinoringo')
            ],[
                'id' => 6,
                'name' => '工藤優作',
                'email' => 'guest5@akinori.com',
                'password' => Hash::make('akinoringo')
            ]                
    	]);
    }
}
