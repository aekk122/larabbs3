<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        
        // 获取用户 ID 数组
        $user_ids = User::all()->pluck('id')->toArray();

        // 获取话题 ID 数组
        $topic_ids = Topic::all()->pluck('id')->toArray();

        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        // 生成数据集合
        $replies = factory(Reply::class)->times(1000)->make()->each( function($reply, $index) use($user_ids, $topic_ids, $faker) {
        	// 从用户 ID 数组中随机选取值插入
        	$reply->user_id = $faker->randomElement($user_ids);
        	// 从话题 ID 。。。
        	$reply->topic_id = $faker->randomElement($topic_ids);
        });

        // 转为数组插入数据库
        Reply::insert($replies->toArray());
    }

}

