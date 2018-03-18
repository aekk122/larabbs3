@include('common.error')
<div class="reply-box">
	<form action="{{ route('replies.store') }}" method="POST" accept-charset="UTF-8">
		{{ csrf_field() }}
		<input type="hidden" name="topic_id" value="{{ $topic->id }}">
		<div class="form-group">
			<textarea name="content" rows="4" class="form-control" placeholder="分享您的想法~"></textarea>
		</div>
		<button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-share"></i>回复</button>
	</form>
</div>