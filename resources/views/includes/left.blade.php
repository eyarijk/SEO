<p class="menu-label">
    Заработать
</p>
<ul class="menu-list">
    <li>
        <a class="{{ Nav::isRoute('surfing.index') }}" href="{{route('surfing.index')}}">Серфинг</a>
        <a class="{{ Nav::isRoute('message.index') }}" href="{{route('message.index')}}">Чтение писем</a>
        <a class="{{ Nav::isRoute('tasks.index') }}" href="{{route('tasks.index')}}">Задания</a>
        <a>Конкурсы</a>
    </li>
    <p class="menu-label">
        Разместить рекламу
    </p>
    <li>
        <a class="{{ Nav::isRoute('surfing.create') }}" href="{{route('surfing.create')}}">Реклама в серфинге</a>
        <a class="{{ Nav::isRoute('message.create') }}" href="{{route('message.create')}}">Письма</a>
        <a class="{{ Nav::isRoute('tasks.create') }}" href="{{route('tasks.create')}}">Задания</a>
        <a class="{{ Nav::isRoute('contexts.create') }}" href="{{route('contexts.create')}}">Контекстная реклама</a>
        <a>Банер</a>
        </ul>
    </li>
    <hr class="time-hr">
    <div class="balance"><i class="fa fa-credit-card" aria-hidden="true"></i> {{money_format('%i',$user->balance)}} ₽</div>
</ul>