<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use App\Models\Link;

class CategoriesController extends Controller
{
    //
    public function show(Request $request, Category $category, Topic $topic, User $user, Link $link) {
    	// 读取分类 ID 关联话题，按 20 每页分页
    	$topics = $topic->withOrder($request->order)->where('category_id', $category->id)->paginate(20);

    	$active_users = $user->getActiveUsers();

    	$links = $link->getAllCached();

    	// 传参
    	return view('topics.index', compact('topics', 'category', 'active_users', 'links'));
    }
}
