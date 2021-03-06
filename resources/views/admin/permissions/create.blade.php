@extends('layouts.manage')

@section('content')
  <div class="flex-container">
    <div class="columns m-t-10">
      <div class="column">
        <h1 class="title">Создать новое разрешение</h1>
      </div>
    </div>
    <hr class="m-t-0">

    <div class="columns">
      <div class="column">
        <form action="{{route('permissions.store')}}" method="POST">
          {{csrf_field()}}

          <div class="block">
                <b-radio v-model="permissionType" name="permission_type" native-value="basic">Основные разрешения</b-radio>
                <b-radio v-model="permissionType" name="permission_type" native-value="crud">CRUD разрешения</b-radio>
          </div>

          <div class="field" v-if="permissionType == 'basic'">
            <label for="display_name" class="label">Имя (отображаемое имя)</label>
            <p class="control">
              <input type="text" class="input" name="display_name" id="display_name">
            </p>
          </div>

          <div class="field" v-if="permissionType == 'basic'">
            <label for="name" class="label">Slug</label>
            <p class="control">
              <input type="text" class="input" name="name" id="name">
            </p>
          </div>

          <div class="field" v-if="permissionType == 'basic'">
            <label for="description" class="label">Описание</label>
            <p class="control">
              <input type="text" class="input" name="description" id="description" placeholder="Опишите, что делает это разрешение">
            </p>
          </div>

          <div class="field" v-if="permissionType == 'crud'">
            <label for="resource" class="label">Resource</label>
            <p class="control">
              <input type="text" class="input" name="resource" id="resource" v-model="resource" placeholder="Имя ресурса">
            </p>
          </div>

          <div class="columns" v-if="permissionType == 'crud'">
            <div class="column is-one-quarter">
                <div class="field">
                  <b-checkbox v-model="crudSelected" native-value="create">Create</b-checkbox>
                </div>
                <div class="field">
                  <b-checkbox v-model="crudSelected"  native-value="read">Read</b-checkbox>
                </div>
                <div class="field">
                  <b-checkbox v-model="crudSelected" native-value="update">Update</b-checkbox>
                </div>
                <div class="field">
                  <b-checkbox v-model="crudSelected" native-value="delete">Delete</b-checkbox>
                </div>
            </div> <!-- end of .column -->

            <input type="hidden" name="crud_selected" :value="crudSelected">

            <div class="column">
              <table class="table" v-if="resource.length >= 3 && crudSelected.length > 0">
                <thead>
                  <th>Имя</th>
                  <th>Slug</th>
                  <th>Описание</th>
                </thead>
                <tbody>
                  <tr v-for="item in crudSelected">
                    <td v-text="crudName(item)"></td>
                    <td v-text="crudSlug(item)"></td>
                    <td v-text="crudDescription(item)"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <button class="button is-primary"><i class="fa fa-file-text  m-r-10"></i>Создать разрешение</button>
        </form>
      </div>
    </div>

  </div> <!-- end of .flex-container -->
@endsection

@section('scripts')
  <script>
    var app = new Vue({
      el: '#app',
      data: {
        permissionType: 'basic',
        resource: '',
        crudSelected: ['create', 'read', 'update', 'delete']
      },
      methods: {
        crudName: function(item) {
          return item.substr(0,1).toUpperCase() + item.substr(1) + " " + app.resource.substr(0,1).toUpperCase() + app.resource.substr(1);
        },
        crudSlug: function(item) {
          return item.toLowerCase() + "-" + app.resource.toLowerCase();
        },
        crudDescription: function(item) {
          return "Разрешить пользователю " + item.toUpperCase() + " " + app.resource.substr(0,1).toUpperCase() + app.resource.substr(1);
        }
      }
    });
  </script>
@endsection
