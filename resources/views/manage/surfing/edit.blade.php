@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            <form action="{{route('surfing.update',$surfing->id)}}" method="post">
                {{ csrf_field() }}
                {{method_field('put')}}
                <b-field class="m-b-0" label="Заголовок серфинга"
                         @if ($errors->first('name'))
                         type="is-danger"
                         message="{{$errors->first('name')}}"
                        @endif>
                    <b-input value="{{$surfing->name}}" placeholder="Заголовок" name="name" maxlength="100"></b-input>
                </b-field>
                <b-field label="URL сайта">
                    <b-input value="{{$surfing->url}}" name="url" placeholder="URL сайта (включая http://)" type="url"></b-input>
                </b-field>
                <b-field label="Время просмотра"
                         @if ($errors->first('window'))
                         type="is-danger"
                         message="{{$errors->first('window')}}"
                        @endif>
                    <b-select name="time" v-model="time">
                        <option
                                :value="20"
                                :key="20">
                            20 сек.
                        </option>
                        <option
                                :value="30"
                                :key="30">
                            30 сек. (+0.002 ₽)
                        </option>
                        <option
                                :value="40"
                                :key="40">
                            40 сек. (+0.004 ₽)
                        </option>
                        <option
                                :value="50"
                                :key="50">
                            50 сек. (+0.006 ₽)
                        </option>
                        <option
                                :value="60"
                                :key="60">
                            60 сек. (+0.008 ₽)
                        </option>
                    </b-select>
                </b-field>
                <b-field label="Последующий переход на сайт"
                         @if ($errors->first('window'))
                         type="is-danger"
                         message="{{$errors->first('window')}}"
                        @endif>
                    <b-select name="window" v-model="window">
                        <option
                                :value="0"
                                :key="0">
                            Нет
                        </option>
                        <option
                                :value="1"
                                :key="1">
                            Да (+0.008 ₽)
                        </option>
                    </b-select>
                </b-field>
                <hr class="time-hr">
                <div>К оплате: <span v-html="total"></span> ₽ за шт.</div>
                <input type="submit" class="button is-primary m-t-10 m-b-30" value="Сохранить серфинг">
            </form>
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
                seen: '',
                window: {{$surfing->window}},
                window_money: @if ($surfing->window) 0.008 @else 0 @endif,
                time: {{$surfing->time}},
                time_money : {{$time}},
                total: {{$surfing->salary / 0.8}},
                start: 0.035,
            },
            watch: {
                time: function (val) {
                    if (val == 20)
                        this.time_money = 0
                    else if (val == 30)
                        this.time_money = 0.002
                    else if (val == 40)
                        this.time_money = 0.004
                    else if (val == 50)
                        this.time_money = 0.006
                    else if (val == 60)
                        this.time_money = 0.008
                    else
                        this.time_money = 0
                    this.total = this.time_money + this.start + this.window_money
                    this.total = Number((this.total).toFixed(3))
                },
                window: function (val) {
                    if (val == 0)
                        this.window_money = 0
                    else
                        this.window_money = 0.008
                    this.total = this.window_money + this.start + this.time_money
                    this.total = Number((this.total).toFixed(3))
                },
            }
        });
    </script>
@endsection