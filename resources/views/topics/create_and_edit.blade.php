@extends('layouts.app')
@section('content')

<div class="container">
    <div class="col-md-offset-2 col-md-8">
        <div class="panel panel-default">
            <div class="panel-body">
                <h2 class="text-center">
                    <i class="glyphicon glyphicon-edit"></i>
                    @if ($topic->id)
                        编辑话题
                    @else
                        新建话题
                    @endif
                </h2>

                <hr>

                @include('common.error')

                @if ($topic->id)
                    <form action="{{ route('topics.update', $topic->id) }}" method="POST" accept-charset="utf-8">
                        {{ method_field('PUT') }} 
                    
                @else
                    <form action="{{ route('topics.store') }}" method="POST" accept-charset="utf-8">
                @endif

                    {{ csrf_field() }}

                    <div class="form-group">
                        <input type="text" name="title" value="{{ old('title', $topic->title) }}" class="form-control" placeholder="请填写标题" required>
                    </div>

                    <div class="form-group">
                        <select name="category_id" class="form-control" required>
                            <option value="" hidden disabled selected>请选择分类</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <textarea name="body" rows="4" class="form-control" placeholder="请填入至少三个字符的内容。" required>{{ old('body', $topic->body) }}</textarea>
                    </div>

                    <div class="well well-sm">
                        <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-ok" aira-hidden="true"></span>保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop