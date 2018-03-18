<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic) {

        // XSS 过滤
        $topic->body = clean($topic->body, 'user_topic_body');

        // 话题摘录生成
    	$topic->excerpt = make_excerpt($topic->body);

    }

    public function saved(Topic $topic) {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if (empty($topic->slug)) {
            
            // 推送队列认为
            dispatch(new TranslateSlug($topic));
        }
    }

    public function deleted(Topic $topic) {
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}