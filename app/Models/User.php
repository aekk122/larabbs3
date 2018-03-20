<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable {
        notify as protected laravelNotify;
    }

    public function notify($instance) {
        // 如果要通知的人是当前用户, 就不必通知了
        if ($this->id === Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    use HasRoles;
    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasManyTopics() {
        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model) {
        return $this->id === $model->user_id;
    }

    public function hasManyReplies() {
        return $this->hasMany(Reply::class, 'user_id');
    }

    public function markAsRead() {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }


    public function setPasswordAttribute($password) {

        // 如果值的长度等于 60，则 不需要加密
        if (strlen($password) != 60) {
            // 不等于 60 ，进行加密
            $password = bcrypt($password);
        }

        $this->attributes['password'] = $password;
    }

    public function setAvatarAttribute($avatar) {
        // 如果值不是 http 开头，则修改
        if ( !starts_with($avatar, 'http')) {
            // 拼接完整的 URL
            $avatar = config('app.url') . '/uploads/images/avatars/' . $avatar;
        }

        $this->attributes['avatar'] = $avatar;
    }
}
