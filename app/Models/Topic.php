<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];


    public function belongsToUser() {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function belongsToCategory() {
    	return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopeWithOrder($query, $order) {
    	// 不同的排序，使用不同的逻辑
    	switch($order) {
    		case 'recent':
    			$query = $this->recent();
    			break;

    		default:
    			$query = $this->recentReplied();
    			break;
    	}

    	// 预加载防止 N+1 问题
    	return $query->with('belongsToUser', 'belongsToCategory');
    }

    public function scopeRecent($query) {
    	// 按照时间排序
    	return $query->orderBy("created_at", 'desc');
    } 

    public function scopeRecentReplied($query) {
    	// 按照最后回复排序
    	return $query->orderBy('updated_at', 'desc');
    }

    public function link($params = []) {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    public function hasManyReplies() {
        return $this->hasMany(Reply::class, 'topic_id');
    }
}
