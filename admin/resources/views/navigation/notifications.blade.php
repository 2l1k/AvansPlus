<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning">{{$totalSystemEvents}}</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">Новых уведомлений: {{$totalSystemEvents}}</li>
        <li>
            <ul class="menu">
                @foreach($system_events as $system_event)
                    <li>
                        <div class="padding-1">
                            {{$system_event->created_at}}: <a href='{{$system_event->edit_url}}'>Перейти к займу</a> - {{$system_event->text}}
                        </div>
                    </li>
                @endforeach
            </ul>
        </li>
        <li class="footer"><a href="{{$system_events_action}}">Посмотреть все</a></li>
    </ul>
</li>