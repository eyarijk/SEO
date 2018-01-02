@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            @include('includes.manage_tabs')
            @if($tasks->count() > 0)
                @foreach($tasks as $task)
                    <form name="status-{{$task->id}}" action="/manage/task/status/" method="post">
                        <input type="hidden" value="{{$task->id}}" name="id">
                        {{csrf_field()}}
                        {{method_field('put')}}
                    </form>
                    <div class="card m-b-10">
                        <div class="card-content">
                            <div class="content">
                                #{{$task->id}}: «{{$task->name}}»
                                <i class="fa fa-money" aria-hidden="true"></i>
                                {{money_format('%i',$task->salary)}} ₽
                                <br>
                                <span><i class="fa fa-th-large" aria-hidden="true"></i> Осталось баланса на {{$task->available}} шт.</span>
                                @if ($task->technology == true)
                                    <br><span><i class="fa fa-th-large" aria-hidden="true"></i> Задание многоразовое. Период раз в {{$task->period}} час(ов).</span>
                                @endif
                                @if ($task->type == true)
                                    <br><span><i class="fa fa-th-large" aria-hidden="true"></i> Автоматическая проверка! Ответ на задание: "{{$task->question}}".</span>
                                @endif
                            </div>
                        </div>
                        <footer class="card-footer">
                            @if ($task->is_show == false)
                                <a onclick="forms['status-{{$task->id}}'].submit();" class="card-footer-item"><i class="fa fa-play" aria-hidden="true"></i><span class="m-l-5">Запуск</span></a>
                            @else
                                <a onclick="forms['status-{{$task->id}}'].submit();" class="card-footer-item"><i class="fa fa-pause" aria-hidden="true"></i><span class="m-l-5">Пауза</span></a>
                            @endif
                            <a href="{{route('taskreport',$task->id)}}" class="card-footer-item"><i class="fa fa-database " aria-hidden="true"></i><span class="m-l-5">Отчёты {{$task->report()->count()}}</span></a>
                            <a href="{{route('taskpay',$task->id)}}" class="card-footer-item"><i class="fa fa-cart-plus " aria-hidden="true"></i><span class="m-l-5">Пополнить</span></a>
                            <form name="form-deleting-{{$task->id}}" action="{{ route('tasks.destroy',$task->id) }}" method="post">
                                <input  type="hidden" name="_method" value="delete">
                                {{ csrf_field() }}
                            </form>
                            <a href="{{route('tasks.edit',$task->id)}}" class="card-footer-item"><i class="fa fa-pencil" aria-hidden="true"></i><span class="m-l-5">Редактировать</span></a>
                            <a class="card-footer-item" onclick="forms['form-deleting-{{$task->id}}'].submit();"><i class="fa fa-trash" aria-hidden="true"></i><span class="m-l-5">Удалить</span></a>
                        </footer>
                    </div>
                @endforeach
            @else
                @include('includes.fail')
            @endif
            {{ $tasks->links() }}
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