<html>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <body>
        {!! $file !!}
        <hr class="time-hr">
        <a href="/banner/redirect/{{$banner->id}}" target="_blank"><img src="{{asset('images/banner/'.$banner->image)}}" width="468" height="60" border="0" alt="{{$banner->name}}" /></a>
        <div style="float: left;" class="timer-surfing m-r-10" id="timer_inp">{{$surfing->time}}</div>
        <form id="validation" method="post" action="/surfing/valid">
            {{csrf_field()}}
            <input type="hidden" name="first" id="first">
            <input type="hidden" name="second" id="second">
            <input type="hidden" name="id" value="{{$surfing->id}}">
            <div style="display: none;" id="valid">
                <div class="field has-addons">
                    <div class="control">
                        <input type="text" id="answer" name="answer" class="input" placeholder="Ответ на вопрос...">
                    </div>
                    <div class="control">
                        <input id="button" class="button is-link" value="Ответить">
                    </div>
                </div>
            </div>
        </form>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
    var first;
    var second;
    var total;
    var json;
    function timer(){
        var obj=document.getElementById('timer_inp');
        obj.innerHTML--;
        if (obj.innerHTML < 10)
            $('#timer_inp').css('padding-left', '17.5px');
        if(obj.innerHTML==0){
            first =  Math.floor(Math.random()*10);
            $("#first").val(first);
            second = Math.floor(Math.random()*10);
            $("#second").val(second);
            total = 'Вопрос: ' + first + ' + ' + second + ' ?';
            $('#valid').attr('style', '');
            $('#answer').attr('placeholder', total);
            $('#timer_inp').attr('style', 'display:none');
            $('#button').attr('type', 'submit');
            setTimeout(function(){},1000);
        } else{
            setTimeout(timer,1000);
        }
    }
    setTimeout(timer,1000);
    var app = new Vue({
        el: '#app',
        data:{},
    });
    $(document).ready(function(){
        $('#validation').on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '/surfing/valid',
                data: $('#validation').serialize(),
                success: function(result){
                    json = JSON.parse(result);
                    if (json['window'])
                        window.open('{{$surfing->url}}');
                    if (json['status'] == 'success')
                        alert('Серфинг онлачен! + {{$surfing->salary}} ₽');
                    else if (json['status'] == 'danger')
                        alert('Серфинг не онлачен!');
                    location.href = '/surfing';
                }
            });
        });
    });
    </script>
    </body>
</html>
