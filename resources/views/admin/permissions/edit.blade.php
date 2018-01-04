@extends('layouts.manage')

@section('content')
  <div class="flex-container">
    <div class="columns m-t-10">
      <div class="column">
        <h1 class="title">Сведения о разрешении</h1>
      </div> <!-- end of column -->
    </div>
    <hr class="m-t-0">

    <form action="{{route('permissions.update', $permission->id)}}" method="POST">
      {{csrf_field()}}
      {{method_field('PUT')}}

      <div class="field">
        <label for="display_name" class="label">Имя (отображаемое имя)</label>
        <p class="control">
          <input type="text" class="input" name="display_name" id="display_name" value="{{$permission->display_name}}">
        </p>
      </div>

      <div class="field">
        <label for="name" class="label">Slug <small>(Невозможно изменить)</small></label>
        <p class="control">
          <input type="text" class="input" name="name" id="name" value="{{$permission->name}}" disabled>
        </p>
      </div>

      <div class="field">
        <label for="description" class="label">Описание</label>
        <p class="control">
          <input type="text" class="input" name="description" id="description" placeholder="Опишите, что делает это разрешение" value="{{$permission->description}}">
        </p>
      </div>

      <button class="button is-primary"><i class="fa fa-floppy-o  m-r-10"></i>Сохранить</button>
    </form>
  </div>
@endsection
