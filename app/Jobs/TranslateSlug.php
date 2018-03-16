<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Handlers\SlugTranslateHandler;
use App\Models\Topic;

class TranslateSlug implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $topic;

    public function __construct(Topic $topic)
    {
        // 队列任务构造器中接受了 Eloquent 模型，将会只序列化模型的ID
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 请求百度 API 接口翻译
        $slug = app(SlugTranslateHandler::class)->translate($this->topic->title);

        // 为了避免模型监控陷入死循环，我们使用 DB 类直接操作数据库
        \DB::table('topics')->where('id', $this->topic->id)->update(['slug' => $slug]);

    }
}
