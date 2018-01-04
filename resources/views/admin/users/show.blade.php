@extends('layouts.manage')

@section('content')
<div class="flex-container">
  <div class="columns m-t-10">
    <div class="column ">
      <h1 class="title">{{$users->name}}</h1>
      <h4 class="subtitle">Информация о пользователе</h4>
    </div>
    <div class="column">
      <a href="{{route('users.edit',$users->id)}}" class="button is-primary is-pulled-right"><i class="fa fa-user m-r-10"></i>Редактировать пользователя</a>
    </div>
  </div>
  <hr class="m-t-0">
  <div class="m-t-10">
      <div class="field">
        <label for="name" class="label">Имя:</label>
        <pre>{{$users->name}}</pre>
      </div>
      <div class="field">
        <label class="label" for="email">Email:</label>
        <pre>{{$users->email}}</pre>
      </div>
      <div class="field">
        <label class="label" for="email">Роли:</label>
        <ul>
          {{$users->roles->count() == 0 ? 'Пользователю еще не назначены роли' : ''}}
          @foreach($users->roles as $role)
            <li>{{$role->display_name}} ({{$role->description}})</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

@endsection
