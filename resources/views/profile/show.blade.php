@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column is-three-fifths">
            <h3 class="title is-3">Привет, {{$user->name}}!</h3>
            <div class="card">
                <div class="card-content">
                    <div class="content">
                        <div class="columns">
                            <div class="column is-3" style="border:1px solid #e7e7e7;width: 270px;">
                                <div>
                                    <h4 class="title is-4">Аватарка</h4>
                                    <figure style="height:170px; margin-left: 35px; width: 170px;" class="image is-128x128">
                                        <img style="height: 100%;width: 100%; border-radius: 50%;"
                                        @if ($user->avatar != null)
                                            src="{{asset('images/user/'.$user->avatar)}}"
                                        @else
                                             src="{{asset('images/icon/avatar.png')}}"
                                        @endif>
                                    </figure>
                                    <form id="avatar" method="post" action="{{route('profile.avatar')}}" enctype="multipart/form-data">
                                        {{csrf_field()}}
                                        <div class="file">
                                            <label class="file-label">
                                                <input class="file-input" type="file" name="image">
                                                <span class="file-cta">
                                                  <span class="file-icon">
                                                    <i class="fa fa-upload"></i>
                                                  </span>
                                                  <span class="file-label">
                                                     Выберите файл...
                                                  </span>
                                                </span>
                                            </label>
                                        </div>
                                        <button class="button is-link m-t-5">Загрузить файл</button>
                                        <hr class="time-hr">
                                        @if ($errors->has("image"))
                                            @foreach($errors->all() as $error)
                                                <ul>
                                                    <li style="color: #ff5145;">{{$error}}</li>
                                                </ul>
                                            @endforeach
                                        @else
                                            <i>Аватар в формате 512x512.</i>
                                        @endif
                                    </form>
                                </div>
                                <hr class="time-hr">
                                <div>
                                    Other...
                                </div>
                            </div>
                            <div class="column m-l-10" style="border: 1px solid #e7e7e7;">
                                <h5 class="title is-5">Личные данные</h5>
                                <hr class="time-hr">
                                <form method="post" action="/profile/edit">
                                    {{csrf_field()}}
                                    <section>
                                        <b-field label="Ник">
                                            <b-input name="name" value="{{$user->name}}"></b-input>
                                        </b-field>
                                        <b-field label="Email">
                                            <b-input name="email" type="email"
                                                     value="{{$user->email}}">
                                            </b-input>
                                        </b-field>
                                        <b-field label="Изменить кошелёк">
                                            <b-select name="money" v-model="money">
                                                <option value="webmoney">Webmoney</option>
                                                <option value="yandex">Yandex</option>
                                                <option value="qiwi">QiWi</option>
                                            </b-select>
                                        </b-field>
                                        <b-field v-if="money == 'webmoney'" label="Webmoney">
                                            <b-input name="webmoney" placeholder="Введите кошелёк. Пример:  R034873236762"></b-input>
                                        </b-field>
                                        <b-field v-if="money == 'yandex'" label="Yandex">
                                            <b-input type="number" name="yandex" placeholder="Введите кошелёк. Пример: 1234567890123"></b-input>
                                        </b-field>
                                        <b-field v-if="money == 'qiwi'" label="QiWi">
                                            <b-input name="qiwi" placeholder="Введите кошелёк. Пример: +380123344567"></b-input>
                                        </b-field>
                                        <b-field label="Введите новый пароль">
                                            <b-input name="newpassword" v-model="password" type="password"></b-input>
                                        </b-field>
                                        <b-field v-if="password" label="Введите повторно новый пароль">
                                            <b-input name="renewpassword" type="password"></b-input>
                                        </b-field>
                                        <button class="button is-link">Сохранить</button>
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
            data:{
                money: '',
                password: ''
            },
        });
        // $('#avatar').on('submit', function(e){
        //     event.preventDefault();
        //
        //         var file_data = $('#sortpicture').prop('files')[0];
        //         var form_data = new FormData();
        //         form_data.append('file', file_data);
        //         alert(form_data);
        //         $.ajax({
        //             url: '/avatar',
        //             dataType: 'text',
        //             cache: false,
        //             contentType: false,
        //             processData: false,
        //             data: form_data,
        //             type: 'post',
        //             success: function(php_script_response){
        //                 alert(php_script_response);
        //             }
        //     });
        // });
    </script>
@endsection
