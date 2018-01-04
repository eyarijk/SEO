<div class="side-menu" id="admin-side-menu">
  <aside class="menu m-t-30 m-l-10">
    <p class="menu-label">
      General
    </p>
    <ul class="menu-list">
      <li><a href="{{route('manage.dashboard')}}" class="{{Nav::isRoute('manage.dashboard')}}">Панель</a></li>
    </ul>

    <p class="menu-label">
      Контент
    </p>
    <ul class="menu-list">
      <li><a href="{{route('posts.index')}}" class="{{Nav::isResource('posts',2)}}">Статьи</a></li>
    </ul>
    <p class="menu-label">
      Администратор
    </p>
    <ul class="menu-list">
      @role('superadministrator|administrator')
        <li><a href="{{route('users.index')}}" class="{{Nav::isResource('users')}}">Пользователи</a></li>
      @endrole
      @role('superadministrator')
      <li><a href="{{route('roles.index')}}" class="{{Nav::isResource('roles')}}">Роли</a></li>
      <li><a href="{{route('permissions.index')}}" class="{{Nav::isResource('permissions')}}">Права доступа</a></li>
      @endrole
        </ul>
      </li>
    </ul>
  </aside>
</div>
