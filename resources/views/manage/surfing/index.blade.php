@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            @include('includes.manage_tabs')
            @if($surfing->count() > 0)
                @foreach($surfing as $surf)
                    <form name="status-{{$surf->id}}" action="{{route('surfingstatus')}}" method="post">
                        <input type="hidden" value="{{$surf->id}}" name="id">
                        {{csrf_field()}}
                    </form>
                    <div class="card m-b-10">
                        <div class="card-content">
                            <div class="content">
                                #{{$surf->id}}: «{{$surf->name}}»
                                <br>
                                <span><i class="fa fa-th-large" aria-hidden="true"></i> Осталось баланса на {{$surf->available}} шт.</span><br>
                            </div>
                        </div>
                        <footer class="card-footer">
                            @if ($surf->is_show == false)
                                <a onclick="forms['status-{{$surf->id}}'].submit();" class="card-footer-item"><i class="fa fa-play" aria-hidden="true"></i><span class="m-l-5">Запуск</span></a>
                            @else
                                <a onclick="forms['status-{{$surf->id}}'].submit();" class="card-footer-item"><i class="fa fa-pause" aria-hidden="true"></i><span class="m-l-5">Пауза</span></a>
                            @endif
                            <a href="{{route('surfingpay',$surf->id)}}" class="card-footer-item"><i class="fa fa-cart-plus " aria-hidden="true"></i><span class="m-l-5">Пополнить</span></a>
                            <form name="form-deleting-{{$surf->id}}" action="{{ route('surfing.destroy',$surf->id) }}" method="post">
                                <input  type="hidden" name="_method" value="delete">
                                {{ csrf_field() }}
                            </form>
                            <a href="{{route('surfing.edit',$surf->id)}}" class="card-footer-item"><i class="fa fa-pencil" aria-hidden="true"></i><span class="m-l-5">Редактировать</span></a>
                            <a class="card-footer-item" onclick="forms['form-deleting-{{$surf->id}}'].submit();"><i class="fa fa-trash" aria-hidden="true"></i><span class="m-l-5">Удалить</span></a>
                        </footer>
                    </div>
                @endforeach
            @else
                @include('includes.fail')
            @endif
            {{ $surfing->links() }}
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