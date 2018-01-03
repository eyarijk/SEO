@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            <form action="{{route('banner.update',$banner->id)}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{method_field('put')}}
                <b-field label="Краткое название"
                         @if ($errors->first('name'))
                         type="is-danger"
                         message="{{$errors->first('name')}}"
                        @endif>
                    <b-input value="{{$banner->name}}" name="name" placeholder="Кратное название"></b-input>
                </b-field>
                <b-field label="Изображение"
                         @if ($errors->first('image'))
                         type="is-danger"
                         message="{{$errors->first('image')}}"
                        @endif>
                    <div class="box">
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
                        Принимаются изображения не превышающие 468×60 пикселей,
                        не более 200 Kb, в форматах: png, jpeg, gif.
                        <p style="text-decoration:underline;">При редактирование выберите ещё раз изображение.</p>
                    </div>
                </b-field>
                <b-field label="URL сайта"
                         @if ($errors->first('url'))
                         type="is-danger"
                         message="{{$errors->first('url')}}"
                        @endif>
                    <b-input value="{{$banner->url}}" name="url" placeholder="URL сайта (включая http://)" type="url"></b-input>
                </b-field>
                <div class="box">
                    Стоимость перехода по ссылке: 0.200 ₽
                </div>
                <input type="submit" class="button is-primary m-b-30" value="Сохранить баннер">

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
            data: {},
        });
    </script>
@endsection