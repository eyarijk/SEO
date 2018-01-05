@extends('layouts.manage')

@section('content')
    <div class="columns">
        <div class="column m-t-20 m-r-30">
            <h3 class="title is-3">Создать категорию</h3>
            <form action="{{route('category.update',$category->id)}}" method="post">
                {{ csrf_field() }}
                {{method_field('put')}}
                <b-field class="m-b-0" label="Название категории"
                         type="is-success">
                    <b-input value="{{$category->name}}" name="name" maxlength="30"></b-input>
                </b-field>
                <div class="field">
                    <b-switch name="is_show">Видимость</b-switch>
                </div>
                <input type="submit" class="button is-primary" value="Сохранить">
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
        });
    </script>
@endsection