@extends('layouts.manage')


@section('content')
<div class="flex-container">
  <div class="columns m-t-10">
    <div class="column">
      <h1 class="title">Создать пользователя</h1>
    </div>
  </div>
  <div class="m-t-10">
    <form class="" action="{{route('users.store')}}" method="post">
      {{csrf_field()}}
      <div class="field">
        <label for="name" class="label">Имя:</label>
        <p class="control"> <input type="text" name="name" class="input" value="" id="name"> </p>
      </div>
      <div class="field">
        <label for="email" class="label">Email:</label>
        <p class="control"> <input type="text" name="email" class="input" value="" id="email"> </p>
      </div>
      <div class="field">
        <label for="password" class="label">Пароль:</label>
        <p class="control">
          <input type="text" name="password" class="input" value="" id="password"  v-if="!auto_password" placeholder="Вручную укажите пароль для этого пользователя">
          <b-checkbox name="auto_generate" class="m-t-15" v-model="auto_password">Автоматическое создание пароля</b-checkbox>
        </p>
      </div>
      <button type="submit" class="button is-primary"><i class="fa fa-file-text  m-r-10"></i>Создать</button>
    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
  var app = new Vue({
    el: '#app',
    data: {
      auto_password: true,
      rolesSelected: [{!! old('roles') ? old('roles') : '' !!}]
    }
  });
</script>
@endsection
