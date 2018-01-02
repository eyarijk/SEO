<p class="menu-label">
        Задание
</p>
<ul class="menu-list">
    <li><a href="{{route('tasks.index')}}"><b>Все задания</b></a></li>
    <li>
        <a class="has-submenu">Параметры</a>
        <ul class="submenu">
            <li><a href="/new">Новые</a></li>
            <li><a href="/reusable">Многоразовые</a></li>
            <li><a href="{{route('favorite.index')}}">Избраное</a></li>
            <li><a href="/paid">Оплачение</a></li>
            <li><a href="/rejected">Отклонены</a></li>
            <li><a href="/deleting">Корзина</a></li>
        </ul>
    </li>
    <li>
        <a class="has-submenu">Категории</a>
        <ul class="submenu">
            @foreach($category as $cat)
                <li><a href="{{route('taskcategory',$cat->slug)}}">{{$cat->name}}</a></li>
            @endforeach
        </ul>
    </li>
    <li>
        <a class="has-submenu">По # задания</a>
        <ul class="submenu">
            <li>
                <form method="post" action="/tasks/id">
                    {{csrf_field()}}
                    <input name="search_id" class="input m-b-5">
                    <button class="button is-link">Найти</button>
                </form>
            </li>
        </ul>
    </li>
    <li>
        <a class="has-submenu">По # рекламодателя</a>
        <ul class="submenu">
            <li>
                <form action="/tasks/user_id" method="post">
                    {{csrf_field()}}
                    <input name="user_id" class="input m-b-5"><button class="button is-link">Найти</button>
                </form>
            </li>
        </ul>
    </li>
    <li>
        <a class="has-submenu">По URL-адресу сайта</a>
        <ul class="submenu">
            <li>
                <form action="/tasks/url" method="post">
                    {{csrf_field()}}
                    <input name="url" class="input m-b-5"><button class="button is-link">Найти</button>
                </form>
            </li>
        </ul>
    </li>
    <hr class="time-hr">
        <span class="p-l-10" style="font-size: 14px;"><i class="fa fa-clock-o" aria-hidden="true"></i> Время на сайте: {{date('H:m')}}</span>
    <hr class="time-hr">
</ul>


@section('submenu')
    <script>
        const accordions = document.getElementsByClassName('has-submenu')

        for (var i = 0; i < accordions.length; i++) {
            accordions[i].onclick = function () {
                this.classList.toggle('is-active');

                const submenu = this.nextElementSibling;
                if (submenu.style.maxHeight) {
                    // menu is open, we need to close it now
                    submenu.style.maxHeight = null
                    submenu.style.marginTop = null
                    submenu.style.marginBottom = null
                } else {
                    // meny is close, so we need to open it
                    submenu.style.maxHeight = submenu.scrollHeight + "px"
                    submenu.style.marginTop = "0.75em"
                    submenu.style.marginBottom = "0.75em"
                }
            }
        }
    </script>
@endsection