@extends('layouts.manage')

@section('content')

    <div class="flex-container">
        <div class="columns m-t-10">
            <div class="column">
                <h1 class="title">Краткая статистика</h1>
            </div>
        </div>
        <hr class="m-t-0">

        <div class="columns is-multiline">
            <div class="column is-one-quarter">
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <h4 class="title"><i class="fa fa-user-circle m-r-5" aria-hidden="true"></i>Пользователей</h4>
                                <p>
                                   <span>Всего: <a href="{{route('users.index')}}">{{$users}}</a></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <h4 class="title"><i class="fa fa-credit-card-alt m-r-5" aria-hidden="true"></i>Всего денег</h4>
                                <p>
                                    <span>Всего: {{$balance}} ₽</a></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <h4 class="title"><i class="fa fa-bars m-r-5" aria-hidden="true"></i>Всего категорий</h4>
                                <p>
                                    <span>Всего: <a href="{{route('category.index')}}">{{$categories}} </a></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <h4 class="title"><i class="fa fa-envelope-open m-r-5" aria-hidden="true"></i>Всего заявок</h4>
                                <p>
                                    <span>Всего: <a href="{{route('contact.admin')}}">{{$contact}}</a></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <h4 class="title"><i class="fa fa-handshake-o  m-r-5" aria-hidden="true"></i>Всего заданий</h4>
                                <p>
                                    <span>Всего: <a href="{{route('tasks.index')}}">{{$tasks}}</a></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <h4 class="title"><i class="fa fa-handshake-o  m-r-5" aria-hidden="true"></i>Всего серфинга</h4>
                                <p>
                                    <span>Всего: <a href="{{route('tasks.index')}}">{{$surfing}}</a></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <h4 class="title"><i class="fa fa-handshake-o  m-r-5" aria-hidden="true"></i>Всего писем</h4>
                                <p>
                                    <span>Всего: <a href="{{route('tasks.index')}}">{{$message}}</a></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="column is-one-quarter">
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <h4 class="title"><i class="fa fa-handshake-o  m-r-5" aria-hidden="true"></i>Реклама</h4>
                                <h6></h6>
                                <p>
                                    <span>Всего: <a href="{{route('tasks.index')}}">{{$all}}</a></span>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
@endsection
