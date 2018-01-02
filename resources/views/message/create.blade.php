@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            <form action="{{route('message.store')}}" method="post">
                {{ csrf_field() }}
                <b-field class="m-b-0" label="Заголовок письма"
                         @if ($errors->first('name'))
                         type="is-danger"
                         message="{{$errors->first('name')}}"
                        @endif>
                    <b-input name="name" maxlength="100"></b-input>
                </b-field>
                <b-field label="Описание"
                         @if ($errors->first('description'))
                         type="is-danger"
                         message="{{$errors->first('description')}}"
                        @endif>
                    <b-input maxlength="3000" name="description" type="textarea"></b-input>
                </b-field>
                <b-field label="URL сайта">
                    <b-input name="url" placeholder="URL сайта (включая http://). Необязательно." type="url"></b-input>
                </b-field>
                <b-field label="Технология доставки письма"
                         @if ($errors->first('delivery'))
                         type="is-danger"
                         message="{{$errors->first('delivery')}}"
                        @endif>
                    <b-select name="delivery" placeholder="Виберите технологию">
                        <option
                                :value="24"
                                :key="24">
                                Доставлять всем каждые 24 часа
                        </option>
                        <option
                                :value="720"
                                :key="720">
                                Доставлять всем 1 в месяц
                        </option>
                    </b-select>
                </b-field>
                <b-field label="Вопрос"
                         @if ($errors->first('question'))
                         type="is-danger"
                         message="{{$errors->first('question')}}"
                        @endif>
                    <b-input name="question" maxlength="300"></b-input>
                </b-field>
                <b-field label="Правильный ответ на письмо"
                         @if ($errors->first('question'))
                         type="is-danger"
                         message="{{$errors->first('answer')}}"
                        @endif>
                    <b-input name="answer" maxlength="300"></b-input>
                </b-field>
                <b-field label="Первый ложный ответ на письмо"
                         @if ($errors->first('question'))
                         type="is-danger"
                         message="{{$errors->first('f_false_answer')}}"
                        @endif>
                    <b-input name="f_false_answer" maxlength="300"></b-input>
                </b-field>
                <b-field label="Второй ложный ответ на письмо"
                         @if ($errors->first('question'))
                         type="is-danger"
                         message="{{$errors->first('s_false_answer')}}"
                        @endif>
                    <b-input name="s_false_answer" maxlength="300"></b-input>
                </b-field>
                <input type="submit" class="button is-primary m-t-10 m-b-30" value="Добавить письмо">
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
            data: {
                seen: '',
            },
        });
    </script>
@endsection