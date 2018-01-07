@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @if (auth()->id())
                @include('includes.left')
            @endif
        </div>
        <div class="column is-three-fifths">
            <section id="scroll" class="scrollbar">
                <div class="chat">
                    <span>Загрузить ещё</span>
                    <ul class="messages">
                        @foreach($messages as $message)
                            <li class="@if($message->user_id == auth()->id()) other @else  you @endif">
                                <a class="user"><img alt="" src="@if($message->user->avatar != null) {{ asset('images/user/'.$message->user->avatar) }} @else {{ asset('images/icon/avatar.png') }} @endif" /></a>
                                <div class="date">
                                    {{$message->created_at->diffForHumans()}}
                                </div>
                                <div class="message">
                                    <label><b><i style="font-size: 15px;" class="fa fa-user-o m-r-5" aria-hidden="true"></i><span>{{$message->user->name}}</span></b></label>
                                    <p>
                                        {{$message->message}}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <hr class="time-hr">
                <p id="typing" style="color: #2d85f1;display: none;"><i class="fa fa-keyboard-o m-r-5" aria-hidden="true"></i><span id="typing_name"></span></p>
            </section>
            <form id="chatform" name="chat" method="POST">
                {{csrf_field()}}
                <div class="field has-addons">
                    <div class="control" style="width: 100%;">
                        <input id="new_message" class="input" name="message" type="text" placeholder="Напишите сообщение...">
                    </div>
                    <div class="control">
                        <input type="submit" id="button-submit" style="display: none;">
                        <a onclick="$('#button-submit').click();" class="button is-info is-outlined">
                            Отправить
                        </a>

                    </div>

                </div>

            </form>
        </div>
        <div class="column">
            @include('includes.context')
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('js/socket.io.js') }}"></script>
    <script>
        var avatar = '@if($user->avatar != null) {{ asset('images/user/'.$message->user->avatar) }} @else {{ asset('images/icon/avatar.png') }} @endif' ;
        function appendMessage(data,who,ava,name,time){
            $('.messages').append(
              $('<li class="'+ who +'"></li>').html('<a class="user"><img src="'+ ava +'" ></a>' +
                  '<div class="date">Только что</div><div class="message"><label><b><i style="font-size: 15px;" class="fa fa-user-o m-r-5" aria-hidden="true"></i><span>' + name + '</span></b></label>' +
                  '<p>'+data+'</p></div>')
            );
            var scrollDiv = document.getElementById("scroll");
            scrollDiv.scrollTo(0, scrollDiv.scrollHeight);
        }
        function hiiden_typing() {
           $('#typing').css('display','none');
        }
        $(document).ready(function() {
            var scrollDiv = document.getElementById("scroll");
            scrollDiv.scrollTo(0, scrollDiv.scrollHeight);
        });
        // Send message
        var socket = io(':6011');
        $(document).ready(function(){
            $("#new_message").keypress(function ()
            {
                socket.emit('chat write','{{$user->name}}');
            });
            $('#chatform').on('submit', function(e){
                e.preventDefault();
                appendMessage($('#new_message').val(),'other',avatar,'{{$user->name}}',new Date());
                if ($('#new_message').val().length > 0){
                    var msg = [$('#new_message').val(),'you',avatar,'{{$user->name}}',new Date()];
                    msg = JSON.stringify(msg);
                    socket.emit('chat message',msg);
                    $.ajax({
                        type: 'POST',
                        url: '/chat/send',
                        data: $('#chatform').serialize(),
                        success: function(result){
                            console.log('send');
                            $('#new_message').val('');
                        }
                    });
                }

            });
        });
        socket.on('chat message',function (msg) {
            var json = JSON.parse(msg);
            appendMessage(json[0],json[1],json[2],json[3],'только что');
        });
        socket.on('chat write',function (msg) {
            if ($('#typing').css('display') == 'none'){
                $('#typing').css('display','block');
                $('#typing_name').text(msg + ' пишет...');
                setTimeout(hiiden_typing, 1000);
            }
        });

    </script>
    <script>
        var app = new Vue({
            el: '#app',
            data:{}
        });
    </script>
@endsection
