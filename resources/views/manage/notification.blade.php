@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column m-t-20 is-three-fifths">
            <b-tabs v-model="activeTab">
                <b-tab-item label="Оповещения">
                    @if ($notifications->count() > 0)
                        <a href="{{route('clear')}}"><i class="fa fa-trash" aria-hidden="true"></i> Удалить всё!</a>
                        @foreach ($notifications as $notification)
                            <div class="notification {{$notification->status}}">
                                <form method="post" action="{{route('notification.destroy',$notification->id)}}">
                                    {{csrf_field()}}
                                    {{method_field('delete')}}
                                    <button style="float: right; top:-4px;" class="delete m-b-10"></button>
                                </form>
                                <span>{!! $notification->description !!}</span>
                                <p>
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                    {{$notification->created_at->diffForHumans()}}
                                </p>
                            </div>
                        @endforeach
                    @else
                        @include('includes.fail')
                    @endif
                    {{$notifications->links()}}
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
            data: {activeTab:0},

        });
    </script>
@endsection