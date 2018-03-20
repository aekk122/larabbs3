<?php

namespace App\Models\Traits;

use Redis;
use Carbon\Carbon;

trait LastActivedAtHelper {

	// 缓存相关
	protected $hash_prefix = "larabbs_last_actived_at_";
	protected $field_prefix = "user_";

	public function recordLastActivedAt() {

		// 获取今日的日期
		$date = Carbon::now()->toDateString();

		// Redis 哈希表的命名，如：larabbs_last_actived_at_2018-3-20
		$hash = $this->hash_prefix . $date;

		// 字段名称，如 user_1
		$field = $this->field_prefix . $this->id;


		// 当前时间，如：2018-3-20 16:41:15
		$now = Carbon::now()->toDateTimeString();

		// 数据写入 Redis，字段已存在则更新
		Redis::hSet($hash, $field, $now);
	}

	public function syncUserActivedAt() {
		// 获取昨天的日期
		$yesterday_date = Carbon::now()->toDateString();

		// Redis 哈希表命名
		$hash = $this->hash_prefix . $yesterday_date;

		// 从 Redis 中获取所有哈希表数据
		$dates = Redis::hGetAll($hash);

		// 遍历，并同步到数据库中
		foreach ($dates as $user_id => $actived_at) {
			// 替换
			$user_id = str_replace($this->field_prefix, '', $user_id);

			// 只有当用户存在时才更新
			if ($user = $this->find($user_id)) {
				$user->last_actived_at = $actived_at;
				$user->save();
			}
		}
	}

	public function getLastActivedAtAttribute($value) {

		// 获取今日的日期
		$date = Carbon::now()->toDateString();

		// Redis 哈希表命名
		$hash = $this->hash_prefix . $date;

		// 字段名称
		$field = $this->field_prefix . $this->id;

		// 三元运算,优先使用 Redis 中的数据，如果没有，读取数据库
		$datetime = Redis::hGet($hash,$field) ?: $value;

		// 如果存在
		if ($datetime) {
			// 返回 Carbon 实体
			return new Carbon($datetime);
		} else {
			// 返回用户注册时间
			return $this->created_at;
		}
	}
}