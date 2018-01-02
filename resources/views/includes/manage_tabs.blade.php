<div class="tabs">
    <ul>
        <li><a href="/manage/tasks">Задания ({{$user->tasks()->count()}})</a></li>
        <li><a>Серфинг</a></li>
        <li><a href="/manage/message">Письма ({{$user->message()->count()}})</a></li>
        <li><a  href="/manage/contexts">Контекстная реклама ({{$user->context()->count()}})</a></li>
        <li><a>Банер</a></li>
    </ul>
</div>