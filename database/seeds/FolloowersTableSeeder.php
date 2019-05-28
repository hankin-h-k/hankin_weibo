<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FolloowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', '776514654@qq.com')->first();
        $user_id = $user->id;
        $other_users = User::where('id', '<>', $user_id)->get();
        $other_user_ids = $other_users->pluck('id')->toArray();

        $user->follow($other_user_ids);

        foreach ($other_users as $other_user) {
        	$other_user->follow($user_id);
        }
    }
}
