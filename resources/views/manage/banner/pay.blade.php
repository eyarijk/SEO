@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            <b-tabs v-model="activeTab">
                <b-tab-item label="Пополнить баланс">
                    <div class="box">
                        <article class="media ">
                            <div class="media-left">
                                <figure class="image is-64x64">
                                    <img src="{{ asset('images/icon/pay.png') }}" class="m-t-15" alt="Image">
                                </figure>
                            </div>
                            <div class="media-content">
                                <form method="post" action="{{route('bannerbuy')}}">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$banner->id}}" name="id">
                                    <div class="content">
                                        <b-field label="Контекстная реклама: {{$banner->name}}">
                                            <b-input v-model="salary" name="count" placeholder="Введите количество..."></b-input>
                                        </b-field>
                                        <button class="button is-link">Пополнить</button>
                                        <span class="button m-l-5" v-html="total"></span>
                                        <span class="button m-l-5">Цена за клик: 0.20 ₽</span>
                                    </div>
                                </form>
                            </div>
                        </article>
                    </div>
                </b-tab-item>
            </b-tabs>
        </div>
        <div class="column">
            @include('includes.context')
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                salary: 0,
                total: 'С счёта будет списано: 0 ₽',
            },
            watch: {
                salary: function (val) {
                    this.total = 'С счёта будет списано: ' + (val * 0.2).toFixed(2) + ' ₽'
                },
            }

        });
    </script>
@endsection