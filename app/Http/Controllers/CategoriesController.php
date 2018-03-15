<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;

class CategoriesController extends Controller
{
    //
    public function show(Request $request, Category $category, Topic $topic) {
    	// 读取分类 ID 关联话题，按 20 每页分页
    	$topics = $topic->withOrder($request->order)->where('category_id', $category->id)->paginate(20);

    	// 传参
    	return view('topics.index', compact('topics', 'category'));
    }
}
