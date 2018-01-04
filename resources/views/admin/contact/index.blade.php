@extends('layouts.manage')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column m-r-50">
            <div class="columns">
                <div class="column">
                    <h1 class="title">Все заявки ({{$contacts->count()}})</h1>
                    <hr class="m-t-0">
                    <div class="box">
                        @if (count($contacts) > 0)
                            @foreach ($contacts as $message)
                                <article class="media">
                                    <div class="media-left">
                                        <figure class="image is-64x64">
                                            <img src="{{ asset('images/icon/message.ico') }}" alt="Image">
                                        </figure>
                                    </div>
                                    <div class="media-content">
                                        <div class="content">
                                            <p class="task">
                                                <i class="fa fa-user" aria-hidden="true"></i> {{$message->email}} <small>{{$message->created_at->diffForHumans()}} <i class="fa fa-clock-o" aria-hidden="true"></i> </small>
                                                <br>
                                               #{{$message->id}} «{{$message->message}}»
                                            </p>
                                        </div>
                                    </div>
                                    <div class="media-right" style="margin-top: 20px; font-size: 20px;">
                                        <a href="{{route('contact.details',$message->id)}}" class="button is-primary"><i class="fa fa-info m-r-5" aria-hidden="true"></i><span>Подробней</span></a>
                                    </div>
                                </article>
                            @endforeach
                        @else
                            @include('includes.fail')
                        @endif
                    </div>
                </div>
            </div>
            {{ $contacts->links() }}
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        var app = new Vue({
            el: '#app',
            data() {
                return {
                    activeTab:0,
                }
            }
        });
    </script>
@endsection