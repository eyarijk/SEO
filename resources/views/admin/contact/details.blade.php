@extends('layouts.manage')

@section('content')
    <div class="card m-r-50 m-t-20">
        <header class="card-header">
            <p class="card-header-title">
                <span>Сообщение #: {{$contact->id}} </span>
            </p>
        </header>
        <div class="card-content">
            <div class="content">
                «{{$contact->message}}»
                <br>
                <time>{{$contact->created_at->diffForHumans()}}</time>
            </div>
        </div>
        <footer id="panel" class="card-footer">
            <form id="delete" action="/admin/contact/delete" method="post">
                {{csrf_field()}}
                <input type="hidden" value="{{$contact->id}}" name="id">
            </form>
            <a id="answer" class="card-footer-item">Ответить</a>
            <a @click="confirm" class="card-footer-item">Удалить</a>
        </footer>
        <footer id="form" style="display: none;padding: 5px; " class="card-footer">
            <form name="send"  style="width: 100%;height: 170px;" action="/admin/contact/answer" method="post">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$contact->id}}">
                <textarea placeholder="Напишите ответ. Минимум 10 символов..." class="textarea" name="answer" style="padding: 10px; width: 100%;height: 170px;resize:none"></textarea>
            </form>
        </footer>
        <footer id="form-second" style="display: none;" class="card-footer">
            <a onclick="forms['send'].submit();" class="card-footer-item">
                <i class="fa fa-check" aria-hidden="true"></i>
                <span class="m-l-5">Ответить</span>
            </a>
            <a class="card-footer-item" id="cancel">
                <i class="fa fa-times" aria-hidden="true"></i>
                <span class="m-l-5">Отмена</span>
            </a>
        </footer>
    </div>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data:{},
            methods: {
                confirm() {
                    $('#admin-side-menu').css('z-index', '-999');
                    this.$dialog.confirm({
                        message: 'Удалить?',
                        onConfirm: () => {
                            $('#delete').submit();
                        }

                })
                },
            }
        });
        $('#answer').on('click', function() {
            $('#form').css('display', 'flex');
            $('#form-second').css('display', 'flex');
            $('#panel').css('display', 'none');
        });
        $('#cancel').on('click', function() {
            $('#form').css('display', 'none');
            $('#form-second').css('display', 'none');
            $('#panel').css('display', 'flex');
        });

    </script>
@endsection