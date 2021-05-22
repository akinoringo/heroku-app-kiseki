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
            ]
    	]);
    }
}
