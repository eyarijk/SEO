@extends('layouts.manage')

@section('content')
  <div class="flex-container">
    <div class="columns m-t-10 m-b-0">
      <div class="column">
        <h1 class="title is-admin is-4">Добавить новую статью</h1>
      </div>
    </div>
    <hr class="m-t-0">

    <form action="{{route('posts.store')}}" method="post">
      {{ csrf_field() }}
      <div class="columns">
        <div class="column is-three-quarters-desktop">
          <b-field>
            <b-input type="text" placeholder="Название статьи" size="is-large" v-model="title">
            </b-input>
          </b-field>
          <b-field class="m-t-40">
            <b-input type="textarea"
                placeholder="Описание..." rows="20">
            </b-input>
          </b-field>
        </div> <!-- end of .column.is-three-quarters -->
        <div class="column is-one-quarter-desktop is-narrow-tablet">
          <div class="card card-widget">
            <div class="author-widget widget-area">
              <div class="selected-author">
                <img style="width: 50px;height: 50px;" src="@if($user->avatar) {{asset('images/user/'.$user->avatar)}} @else {{asset('images/icon/avatar.png')}} @endif"/>
                <div class="author">
                  <h4>Mr</h4>
                  <p class="subtitle">
                    {{$user->name}}
                  </p>
                </div>
              </div>
            </div>
            <div class="post-status-widget widget-area">
              <div class="status">
                <div class="status-icon">
                  <b-icon  pack="fa" icon="floppy-o" size="is-large"></b-icon>
                </div>
                <div class="status-details">
                  <h4><span class="status-emphasis">Не сохранено</span></h4>
                  <p>Обновлено несколько секунд назад
                </div>
              </div>
            </div>
            <div class="publish-buttons-widget widget-area">
              <div class="secondary-action-button">
                <button class="button is-info is-outlined is-fullwidth">Save</button>
              </div>
              <div class="primary-action-button">
                <button class="button is-primary is-fullwidth">Публиковать</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>


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
