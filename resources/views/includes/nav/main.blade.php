<nav class="navbar has-shadow is-light" >
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item is-paddingless brand-item " href="/">
                <span class="is-tab navbar-item" >SEO</span>
            </a>
            <button class="button navbar-burger is-light">
                <span></span>
                <span></span>
                <span></span>
            </button>
            </div>
            <div class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item is-tab {{ Nav::isRoute('tasks.index') }}" href="{{route('tasks.index')}}">Заработать</a>
                    <a class="navbar-item is-tab">Форум</a>
                    <a class="navbar-item is-tab">Выплаты</a>
                    <a href="/rules" class="navbar-item is-tab">Правила</a>
                    <a href="/contact" class="navbar-item is-tab">Контакты</a>
                </div> <!-- end of .navbar-start -->

                <div class="navbar-end nav-menu" style="overflow: visible">
                    @guest
                        <a href="{{route('login')}}" class="navbar-item is-tab {{ Nav::isRoute('login') }}">Вход</a>
                        <a href="{{route('register')}}" class="navbar-item is-tab" {{ Nav::isRoute('register') }}>Регистрация</a>
                    @else
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link"><i class="fa fa-user-circle-o m-r-5"></i> Hey {{Auth::user()->name}} ({{$user->notification()->count() + $user->tasks()->count()}})</a>
                            <div class="navbar-dropdown is-right" >
                                <a href="/profile" class="navbar-item">
                                    <span class="icon">
                                      <i class="fa fa-fw fa-user m-r-5"></i>
                                    </span>Профиль
                                </a>
                                <a href="/notification" class="navbar-item">
                                    <span class="icon">
                                      <i class="fa fa-fw fa-bell m-r-5"></i>
                                    </span>Oповещения ({{$user->notification()->count()}})
                                </a>
                                <a href="/manage/tasks" class="navbar-item">
                                    <span class="icon">
                                      <i class="fa fa-cart-plus  m-r-5"></i>
                                    </span>Реклама ({{$user->tasks()->count()}})
                                </a>
                                <a href="" class="navbar-item">
                                    <span class="icon">
                                      <i class="fa fa-comments  m-r-5"></i>
                                    </span>Сообщения
                                </a>
                                <a href="" class="navbar-item">
                                    <span class="icon">
                                      <i class="fa fa-fw fa-cog m-r-5"></i>
                                    </span>Настройки
                                </a>
                                <hr class="navbar-divider">
                                <a href="{{route('logout')}}" class="navbar-item" onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                                    <span class="icon">
                                        <i class="fa fa-fw fa-sign-out m-r-5"></i>
                                    </span>
                                    Выход
                                </a>
                            @include('includes.forms.logout')
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
