<?php

use Illuminate\Database\Seeder;
use App\Repositories\Eloquent\EloquentUserRepository;
Use App\User;
use Illuminate\Support\Facades\Hash;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(EloquentUserRepository $userRepo)
    {
        User::truncate();
        $user = ['email'=>'admin@gmail.com','password'=>Hash::make('password'),'name'=>'Administrator'];
        user::create($user);
    }
}
