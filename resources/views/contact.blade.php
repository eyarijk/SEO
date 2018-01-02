@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @if(auth()->id())
                @include('includes.left')
            @endif
        </div>
        <div class="column is-three-fifths">
            <form id="contactform" method="POST" class="validateform">
                {{ csrf_field() }}
                <b-field label="E-Mail">
                    <b-input name="email" type="email" maxlength="30" placeholder="Введите Ваш E-Mail"></b-input>
                </b-field>
                <b-field label="Сообщение">
                    <b-input maxlength="500" name="message" type="textarea" placeholder="Сообщение..."></b-input>
                </b-field>
                <input type="submit" id="status" style="display: none;">
                <a id="send" onclick="getElementById('status').click();" class="button is-link is-outlined">Отправить</a>
            </form>
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
            data:{}
        });
        $(document).ready(function(){
            $('#contactform').on('submit', function(e){
                e.preventDefault();
                $('#send').removeClass('is-outlined').addClass('is-loading');
                $.ajax({
                    type: 'POST',
                    url: '/contact/send',
                    data: $('#contactform').serialize(),
                    success: function(result){
                        $('#send').removeClass('is-loading').addClass('is-outlined');
                        document.getElementById('contactform').reset();
                    }
                });
            });
        });
    </script>
@endsection
