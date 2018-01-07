@extends('layouts.manage')

@section('content')
<div class="flex-container">
  <div class="columns m-t-10">
    <div class="column ">
      <h1 class="title">Управление пользователями</h1>
    </div>
    <div class="column">
      <a href="{{route('users.create')}}" class="button is-primary is-pulled-right"><i class="fa fa-user-plus m-r-10"></i>Создать пользователя</a>
    </div>
  </div>
  <form name="search" action="{{route('users.search')}}" method="post">
    {{csrf_field()}}
    <div class="field has-addons">
      <p class="control">
      <span class="select">
        <select name="select">
          <option value="ID">ID</option>
          <option value="email">E-Mail</option>
          <option value="name">Имя</option>
        </select>
      </span>
      </p>
      <div class="control">
        <input class="input" type="text" name="value" placeholder="Введите значение...">
      </div>
      <div class="control">
        <a onclick="forms['search'].submit();" class="button is-info">
          Найти
        </a>
      </div>
    </div>
  </form>
  <hr class="m-t-5">
      <div class="card">
        <div class="card-content">
          <table class="table is-narrow">
            <thead>
              <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Дата создания</th>
                <th>Действия</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
              <tr>
                <th>{{$user->id}}</th>
                <th>{{$user->name}}</th>
                <th>{{$user->email}}</th>
                <th>{{$user->created_at->toFormattedDateString()}}</th>
                <td class="has-text-right"><a class="button is-outlined is-small m-r-5" href="{{route('users.show', $user->id)}}">View</a><a class="button is-outlined is-small" href="{{route('users.edit', $user->id)}}">Edit</a></td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </div>
      </div>
      @if ($paginate == true)
          {{$users->links()}}
        @endif
    </div>


@endsection
