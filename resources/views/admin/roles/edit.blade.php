@extends('layouts.manage')

@section('content')

<div class="flex-container">
  <div class="columns m-t-10">
    <div class="column">
      <h1 class="title">Редактировать {{$role->display_name}} роль</h1>
    </div>
  </div>
  <hr class="m-t-0">
  <form class="" action="{{route('roles.update',$role->id)}}" method="post">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <div class="columns">
      <div class="column">
        <div class="box">
          <article class="media">
            <div class="media-content">
              <div class="content">
                <h2 class="titile">Сведения о роли:</h2>
                <div class="field">
                  <p class="control">
                    <label for="display_name" class="label">Имя</label>
                    <input type="text" name="display_name" value="{{$role->display_name}}" class="input" id="display_name">
                  </p>
                </div>
                <div class="field">
                  <p class="control">
                    <label for="name" class="label">Slug (Невозможно отредактировать)</label>
                    <input type="text" name="name" value="{{$role->name}}" class="input" disabled id="name">
                  </p>
                </div>
                <div class="field">
                  <p class="control">
                    <label for="description" class="label">Описание</label>
                    <input type="text" name="description" value="{{$role->description}}" class="input" id="description">
                  </p>
                </div>
                <input type="hidden" name="permissions" :value="permissionsSelected">
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
    <div class="columns">
      <div class="column">
        <div class="box">
          <article class="media">
            <div class="media-content">
              <div class="content">
                <h2 class="title">Права доступа:</h2>
                  @foreach ($permissions as $permission)
                    <div class="field">
                      <b-checkbox v-model="permissionsSelected" :native-value="{{$permission->id}}">{{$permission->display_name}} <em>({{$permission->description}})</em></b-checkbox>
                    </div>
                  @endforeach
                </ul>
              </div>
            </div>
          </article>
        </div>
        <button class="button is-primary" name="button"><i class="fa fa-floppy-o  m-r-10"></i>Сохранить</button>
      </div>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
  var app = new Vue({
  el:'#app',
  data:{
    permissionsSelected:{!!$role->permissions->pluck('id')!!}
  }
  });
</script>
@endsection
