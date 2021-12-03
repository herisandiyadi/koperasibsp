<li>
	<a href="{{url('notification/'.$id.'/resolve')}}" onmouseover="markAsRead('{{$id}}')">
		<i class="fa {{$icon}} {{$iconColor}}"></i> {{$content}}
	</a>
</li>
