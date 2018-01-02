@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            @if($tasks->count() > 0)
                @foreach($tasks as $task)
                    <form name="reject-{{$task->id}}" action="/manage/task/danger/" method="post">
                        <input type="hidden" value="{{$task->id}}" name="id">
                        {{csrf_field()}}
                        {{method_field('put')}}
                    </form>
                    <form name="success-{{$task->id}}" action="/manage/task/success/" method="post">
                        <input type="hidden" value="{{$task->id}}" name="id">
                        {{csrf_field()}}
                        {{method_field('put')}}
                    </form>
                    <div class="card m-b-10">
                        <header class="card-header">
                            <p class="card-header-title">
                                <i class="fa fa-user" aria-hidden="true"></i><span class="m-l-5"> Исполнитель: {{$task->user->name}}</span>
                            </p>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                «{{$task->answer}}»
                            </div>
                        </div>
                        <div class="card-footer" style="padding: 10px;">
                            <div class="columns">
                                <div class="column is-9 m-l-10 m-r-50">
                                    <time><i class="fa fa-clock-o" aria-hidden="true"></i> Начало: {{$task->created_at->diffForHumans()}}</time>
                                </div>
                                <div class="column is-9">
                                    <time><i class="fa fa-clock-o" aria-hidden="true"></i> Конец: {{$task->updated_at->diffForHumans()}}</time>
                                </div>
                            </div>
                        </div>
                        <footer class="card-footer">
                            <a onclick="forms['success-{{$task->id}}'].submit();" class="card-footer-item">
                                <i class="fa fa-check" aria-hidden="true"></i>
                                <span class="m-l-5">Подтвердить</span>
                            </a>
                            <a onclick="forms['reject-{{$task->id}}'].submit();" class="card-footer-item">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                <span class="m-l-5">Отклонить</span>
                            </a>
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