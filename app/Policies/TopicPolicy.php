<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    public function update(User $currentUser, Topic $topic)
    {
        // return $topic->user_id == $user->id;
        return $currentUser->isAuthorOf($topic);
    }

    public function destroy(User $currentUser, Topic $topic)
    {
        return $currentUser->isAuthorOf($topic);
    }


}
