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
                        <span class="m-l-5"> Письмо: #{{$message->id}}: «{{$message->name}}»
                            <i class="fa fa-money m-l-5" aria-hidden="true"></i>
                            {{money_format('%i',0.18)}} ₽
                        </span>
                    </p>
                </header>
                <header class="card-header">
                    <p class="card-header-title">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span class="m-l-5 m-r-10"> Добавил: {{$message->user->name}}</span>
                        <i class="fa fa-clock-o m-r-5" aria-hidden="true"></i>
                        <time>{{$message->created_at->diffForHumans()}}</time>
                    </p>
                    <p class="card-header-title">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <span class="m-l-5"> Одобрено: {{$message->success}} /</span>
                        <i class="fa fa-times m-l-5" aria-hidden="true"></i>
                        <time class="m-l-5">Отклонено: {{$message->danger}} /</time>
                        <i class="fa fa-comments-o m-l-5" aria-hidden="true"></i>
                        <time class="m-l-5">Отзывы: <a href="{{route('messagecomments',$message->slug)}}">{{$message->comments->count()}}</a></time>
                    </p>
                </header>
                <div class="card-content">
                    <div class="content">
                        <span>«{{$message->description}}»</span>
                        <br>
                        <p style="border-top: 1px solid #dadada;" class="m-t-20 p-t-20">
                            <b>Вопрос:</b>
                            <br>
                            <span class="p-l-10">{{$message->question}}</span>
                        </p>
                    </div>

                </div>
                    @if (is_array($answers))
                    <footer class="card-footer">
                        <ul class="m-l-20 m-t-10">
                            @foreach($answers as $answer)
                                <li>
                                    <b-radio name="answer" v-model="radio"
                                             native-value="{{$answer}}">
                                        {{$answer}}
                                    </b-radio>
                                </li>
                            @endforeach
                        </ul>
                    </footer>
                    <form action="/manage/message/work" method="post">
                        {{csrf_field()}}
                        <input type="hidden" value="{{$message->id}}" name="id">
                        <input type="hidden" name="answer" v-model="radio">
                        <button class="button is-link m-l-15 m-t-15 m-b-15">Отправить</button>
                    </form>
                    @else
                    <footer class="card-footer">
                           @if ($answers = 'success')
                            <a class="card-footer-item">
                                <i class="fa fa-money" aria-hidden="true"></i>
                                <span class="m-l-5">Письмо оплачено!</span>
                            </a>
                           @elseif ($answers == 'danger')
                            <a class="card-footer-item">
                                <i class="fa fa-times" aria-hidden="true"></i>
                                <span class="m-l-5">Письмо отклонено!</span>
                            </a>
                           @endif
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
            data: {
                radio: ''
            },

        });
    </script>
@endsection