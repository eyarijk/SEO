@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            <div class="card m-b-10">
                <header class="card-header">
                    <p class="card-header-title">
                        <i class="fa fa-bookmark" aria-hidden="true"></i>
                        <span class="m-l-5"> Задание: #{{$task->id}}: «{{$task->name}}»
                            <i class="fa fa-money m-l-5" aria-hidden="true"></i>
                            {{money_format('%i',$task->salary)}} ₽
                            @if ($task->type == true)
                                <i class="fa fa-circle m-l-5" aria-hidden="true"></i>
                                Атоматическая проверка.
                            @endif
                            @if ($task->technology == true)
                                <i class="fa fa-circle m-l-5" aria-hidden="true"></i>
                                Задание многоразовое.
                            @endif
                        </span>
                    </p>
                </header>
                <header class="card-header">
                    <p class="card-header-title">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span class="m-l-5 m-r-10"> Добавил: {{$task->user->name}}</span>
                        <i class="fa fa-clock-o m-r-5" aria-hidden="true"></i>
                        <time>{{$task->created_at->diffForHumans()}}</time>
                    </p>
                    <p class="card-header-title">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <span class="m-l-5"> Одобрено: {{$task->success}} /</span>
                        <i class="fa fa-times m-l-5" aria-hidden="true"></i>
                        <time class="m-l-5">Отклонено: {{$task->danger}} /</time>
                        <i class="fa fa-comments-o m-l-5" aria-hidden="true"></i>
                        <time class="m-l-5">Отзывы: <a href="{{route('taskcomments',$task->slug)}}">{{$task->comments->count()}}</a></time>
                    </p>
                </header>
                <div class="card-content">
                    <div class="content">
                        <span>«{{$task->description}}»</span>
                        <br>
                        <p style="border-top: 1px solid #dadada;" class="m-t-20 p-t-20">
                            <b>Что нужно указать в отчёте:</b>
                            <br>
                            <span class="p-l-10">{{$task->answer}}</span>
                        </p>
                    </div>
                </div>
                @if ($status == 'limit')
                    <footer class="card-footer">
                        <a class="card-footer-item">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <span class="m-l-5">Задание будет доступно через {{$limittime}} часов!</span>
                        </a>
                    </footer>
                @elseif ($task->available < 1 && !isset($report))
                    <footer class="card-footer">
                        <a class="card-footer-item">
                            <i class="fa fa-hdd-o" aria-hidden="true"></i>
                            <span class="m-l-5">Задание приостановлено!</span>
                        </a>
                    </footer>
                @elseif ($status == 'working')
                    <form name="report" action="{{ route('worktask.update',$report->id) }}" method="post">
                        {{ csrf_field() }}
                        {{method_field('put')}}
                        <footer class="card-footer">
                            <textarea name="answer" style="width: 100%;height: 170px;resize: none"></textarea>
                        </footer>
                        <footer class="card-footer">
                            <a onclick="forms['report'].submit();" class="card-footer-item">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                <span class="m-l-5">Отправить отчёт</span>
                            </a>
                            <a class="card-footer-item" onclick="forms['cancel'].submit();">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                <span class="m-l-5">Отказать от выполнения</span>
                            </a>
                        </footer>
                    </form>
                    <form name="cancel" action="{{ route('worktask.destroy',$report->id) }}" method="post">
                        {{csrf_field()}}
                        {{method_field('delete')}}
                    </form>
                @elseif($status == 'new')
                    <form method="post" name="start" action="{{route('worktask.store')}}">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$task->id}}" name="id">
                        <footer class="card-footer">
                            <a onclick="forms['start'].submit();" class="card-footer-item"
                            @if (isset($task->url))
                                target="_blank" href="{{$task->url}}"
                            @endif>
                                <i class="fa fa-check" aria-hidden="true"></i>
                                <span class="m-l-5">Начать выполнение</span>
                            </a>
                        </footer>
                    </form>
                @elseif($status == 'success')
                    <footer class="card-footer">
                        <a class="card-footer-item">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <span class="m-l-5">Задание оплачено!</span>
                        </a>
                    </footer>
                @elseif($status == 'rejected')
                    <footer class="card-footer">
                        <a class="card-footer-item">
                            <i class="fa fa-times" aria-hidden="true"></i>
                            <span class="m-l-5">Задание отклонено!</span>
                        </a>
                    </footer>
                @else
                    <footer class="card-footer">
                        <a class="card-footer-item">
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                            <span class="m-l-5">Задание на проверке!</span>
                        </a>
                    </footer>
                @endif
            </div>
        </div>
        <div class="column">
            @include('includes.context')
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {},

        });
    </script>
@endsection