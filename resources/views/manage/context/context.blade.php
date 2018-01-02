@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            @include('includes.manage_tabs')
            @if($context->count() > 0)
                @foreach($context as $cont)
                    <form name="status-{{$cont->id}}" action="/manage/context/status/" method="post">
                        <input type="hidden" value="{{$cont->id}}" name="id">
                        {{csrf_field()}}
                        {{method_field('put')}}
                    </form>
                    <div class="card m-b-10">
                        <div class="card-content">
                            <div class="content">
                                #{{$cont->id}}: «{{$cont->description}}»
                                <br>
                                <span>
                                    <i class="fa fa-th-large" aria-hidden="true"></i>
                                    Осталось баланса на {{$cont->available}} клик(ов).
                                </span>
                            </div>
                        </div>
                        <footer class="card-footer">
                            @if ($cont->is_show == false)
                                <a onclick="forms['status-{{$cont->id}}'].submit();" class="card-footer-item">
                                    <i class="fa fa-play" aria-hidden="true"></i>
                                    <span class="m-l-5">Запуск</span>
                                </a>
                            @else
                                <a onclick="forms['status-{{$cont->id}}'].submit();" class="card-footer-item">
                                    <i class="fa fa-pause" aria-hidden="true"></i>
                                    <span class="m-l-5">Приостановить</span>
                                </a>
                            @endif
                            <a href="{{route('contextpay',$cont->id)}}" class="card-footer-item">
                                <i class="fa fa-cart-plus " aria-hidden="true"></i>
                                <span class="m-l-5">Пополнить</span>
                            </a>
                            <form name="form-deleting-{{$cont->id}}" action="{{ route('contexts.destroy',$cont->id) }}" method="post">
                                <input  type="hidden" name="_method" value="delete">
                                {{ csrf_field() }}
                            </form>
                            <a href="{{route('contexts.edit',$cont->id)}}" class="card-footer-item"><i class="fa fa-pencil" aria-hidden="true"></i><span class="m-l-5">Редактировать</span></a>
                            <a class="card-footer-item" onclick="forms['form-deleting-{{$cont->id}}'].submit();">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                <span class="m-l-5">Удалить</span>
                            </a>
                        </footer>
                    </div>
                @endforeach
            @else
                @include('includes.fail')
            @endif
            {{ $context->links() }}
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
            data: {},

        });
    </script>
@endsection