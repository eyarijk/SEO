@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            <form action="{{route('tasks.update',$task->id)}}" method="post">
                {{ csrf_field() }}
                {{method_field('put')}}
                <b-field class="m-b-0" label="Заголовок задания"
                         @if ($errors->first('name'))
                         type="is-danger"
                         message="{{$errors->first('name')}}"
                        @endif>
                    <b-input name="name" value="{{$task->name}}" maxlength="100"></b-input>
                </b-field>
                <b-field label="Категория"
                     @if ($errors->first('category_id'))
                         type="is-danger"
                         message="{{$errors->first('category_id')}}"
                    @endif>
                    <div class="select">
                        <select name="category_id">
                            @foreach($category as $cat)
                                <option value="{{$cat->id}}"
                                    @if ($cat->id == $task->category_id)
                                    selected="selected"
                                    @endif
                                    >
                                    {{$cat->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </b-field>
                <b-field label="Описание"
                         @if ($errors->first('description'))
                         type="is-danger"
                         message="{{$errors->first('description')}}"
                        @endif>
                    <b-input maxlength="3000" value="{{$task->description}}" name="description" type="textarea"></b-input>
                </b-field>
                <b-field label="Что должен указать исполнитель для проверки выполнения задания ↓"
                         @if ($errors->first('answer'))
                         type="is-danger"
                         message="{{$errors->first('answer')}}"
                        @endif>
                    <b-input maxlength="500" value="{{$task->answer}}" name="answer" type="textarea"></b-input>
                </b-field>
                <hr>
                <b-field class="m-b-0" label="Вознаграждение исполнителю"
                         @if (session('salary'))
                         type="is-danger"
                         message="{{session('salary')}}"
                        @endif>
                    <b-input name="salary"  v-model="salary"></b-input>
                </b-field>
                <b-field label="URL сайта">
                    <b-input value="{{$task->url}}" name="url" placeholder="URL сайта (включая http://)" type="url"></b-input>
                </b-field>
                <b-field label="Технология выполнения задания"
                         @if ($errors->first('technology'))
                         type="is-danger"
                         message="{{$errors->first('technology')}}"
                        @endif>
                    <b-select v-model="seen" name="technology" placeholder="Виберите технологию">
                        <option
                                :value="0"
                                :key="0">
                            Один пользователь — одно выполнение
                        </option>
                        <option
                                :value="1"
                                :key="1">
                            Задание можно выполнять многократно
                        </option>
                    </b-select>
                </b-field>
                <b-field v-if="seen" label="Интервал многократных выполнений"
                         @if ($errors->first('period'))
                         type="is-danger"
                         message="{{$errors->first('period')}}"
                        @endif>
                    <b-select v-model="period"  name="period" placeholder="Период" >
                        <option
                                :value="0"
                                :key="0">
                            Разрешено сразу после проверки
                        </option>
                        <option
                                :value="240"
                                :key="240">
                            Не менеее 10 дней после подачи отчёта
                        </option>
                        <option
                                :value="168"
                                :key="168">
                            Не менеее 7 дней после подачи отчёта
                        </option>
                        <option
                                :value="120"
                                :key="120">
                            Не менеее 5 дней после подачи отчёта
                        </option>
                        <option
                                :value="48"
                                :key="48">
                            Не менеее 48 часов после подачи отчёта
                        </option>
                        <option
                                :value="24"
                                :key="24">
                            Не менеее 24 часов после подачи отчёта
                        </option>
                        <option
                                :value="12"
                                :key="12">
                            Не менеее 12 часов после подачи отчёта
                        </option>
                        <option
                                :value="6"
                                :key="6">
                            Не менеее 6 часов после подачи отчёта
                        </option>
                        <option
                                :value="1"
                                :key="1">
                            Не менеее 1 часа после подачи отчёта
                        </option>
                    </b-select>
                </b-field>
                <b-field label="Максимальный срок, отведённый на выполнение задания"
                         @if ($errors->first('time'))
                         type="is-danger"
                         message="{{$errors->first('time')}}"
                        @endif>
                    <b-select  v-model="time" name="time" name="time">
                        <option
                                :value="720"
                                :key="720">
                            30 дней
                        </option>
                        <option
                                :value="480"
                                :key="480">
                            20 дней
                        </option>
                        <option
                                :value="240"
                                :key="240">
                            10 дней
                        </option>
                        <option
                                :value="168"
                                :key="168">
                            7 дней
                        </option>
                        <option
                                :value="120"
                                :key="120">
                            5 дней
                        </option>
                        <option
                                :value="72"
                                :key="72">
                            3 дня
                        </option>
                        <option
                                :value="48"
                                :key="48">
                            1 дня
                        </option>
                        <option
                                :value="24"
                                :key="24">
                            1 день
                        </option>
                        <option
                                :value="12"
                                :key="12">
                            12 часов
                        </option>
                        <option
                                :value="6"
                                :key="6">
                            6 часов
                        </option>
                        <option
                                :value="2"
                                :key="2">
                            2 часа
                        </option>
                        <option
                                :value="1"
                                :key="1">
                            1 час
                        </option>
                    </b-select>
                </b-field>
                <b-field label="Технология подтверждения"
                         @if ($errors->first('type'))
                         type="is-danger"
                         message="{{$errors->first('type')}}"
                        @endif>
                    <b-select v-model="auto" name="type" placeholder="Виберите технологию">
                        <option
                                :value="0"
                                :key="0">
                            Ручная проверка
                        </option>
                        <option
                                :value="1"
                                :key="1">
                            Автоматическая проверка
                        </option>
                    </b-select>
                </b-field>
                <b-field v-if="auto" label="Ответ на задание"
                         @if ($errors->first('question'))
                         type="is-danger"
                         message="{{$errors->first('question')}}"
                        @endif>
                    <b-input value="{{$task->question}}" name="question" maxlength="300"></b-input>
                </b-field>
                <hr class="time-hr">
                <p style="text-align: center; font-size: 20px;">
                    <i class="fa fa-money" aria-hidden="true"></i>
                    Стоимость одного выполнения:
                    <span style="color: #00deb4;" v-html="total">
                    </span>
                </p>
                <hr class="time-hr">
                <input type="submit" class="button is-primary m-t-10 m-b-30" value="Сохранить изменения">
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
                seen: {{$task->technology}},
                salary: {{$task->salary}},
                total: {{$task->salary * 1.2}},
                auto: {{$task->type}},
                period: @if ($task->period != null) {{$task->period}} @else 0 @endif,
                time: {{$task->time}},
            },
            watch: {
                salary: function (val) {
                    this.total = val*0.2 + Number(val)
                },
            }
        });
    </script>
@endsection