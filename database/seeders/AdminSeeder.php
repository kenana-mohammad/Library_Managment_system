<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\models\user;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user=User::create([
            'name'=>'admin',
            'email' => 'admin@gmail.com',
            'password'=>Hash::make('12345678'),
            'role' =>'admin'
        ]);
        $token=Auth::login($user);
    }
}
