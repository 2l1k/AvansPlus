@if($dialing_comments)
    <table class="table">
        @foreach($dialing_comments as $dialing_comment)
            <tr>
                <td>{{$dialing_comment->created_at}}</td>
                <td>{{$dialing_comment->comment}}</td>
                <td>{{!empty($dialing_comment->dialingStatus) ? $dialing_comment->dialingStatus->text : ""}}</td>
            </tr>
        @endforeach
    </table>
@endif