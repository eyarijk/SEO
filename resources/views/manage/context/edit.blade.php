@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            <form action="{{route('contexts.update',$context->id)}}" method="post">
                {{method_field('put')}}
                {{ csrf_field() }}
                <b-field label="Описание"
                         @if ($errors->first('description'))
                         type="is-danger"
                         message="{{$errors->first('description')}}"
                        @endif>
                    <b-input value="{{$context->description}}" placeholder="Краткое описание..." maxlength="200" name="description" type="textarea"></b-input>
                </b-field>
                <b-field label="URL сайта"
                         @if ($errors->first('url'))
                         type="is-danger"
                         message="{{$errors->first('url')}}"
                        @endif>
                    <b-input value="{{$context->url}}" name="url" placeholder="URL сайта (включая http://)" type="url"></b-input>
                </b-field>
                <input type="submit" class="button is-primary m-t-10 m-b-30" value="Сохранить">
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