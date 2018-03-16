@extends('layouts.app')
@section('content')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/simditor.css') }}">
@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/simditor.js') }}"></script>

    <script>
        $(document).ready(function() {
            var editor = new Simditor({
                textarea: $('#editor'),
                upload: {
                    url: "{{ route('topics.upload_image') }}",
                    params: {_token: '{{ csrf_token() }}'},
                    fileKey: 'upload_file',
                    connectionCount: 3,
                    leaveConfirm: '文件上传中，关闭此页面将取消上传！'
                },
                pasteImage: true,
            });
        });
    </script>
@stop

<div class="container">
    <div class="col-md-offset-1 col-md-10">
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
                        <textarea name="body" id="editor" rows="4" class="form-control" placeholder="请填入至少三个字符的内容。" required>{{ old('body', $topic->body) }}</textarea>
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