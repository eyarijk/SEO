@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @if (auth()->id())
                @include('includes.left')
            @endif
        </div>
        <div class="column is-three-fifths">
            <b-tabs v-model="activeTab">
                <b-tab-item label="Новости">
                    <div class="columns">
                        <div class="column">
                            <div class="box">
                                @if (count($posts) > 0)
                                    @foreach ($posts as $message)
                                        <article class="media">
                                            <div class="media-left">
                                                <figure class="image is-64x64">
                                                    <img src="{{ asset('images/icon/news.png') }}" alt="Image">
                                                </figure>
                                            </div>
                                            <div class="media-content">
                                                <div class="content">
                                                    <p class="task">
                                                        <i class="fa fa-user m-r-5" aria-hidden="true"></i><a href="{{route('message.show',$message->slug)}}"> </a><small>{{$message->user->name}} {{$message->created_at->diffForHumans()}} <i class="fa fa-clock-o" aria-hidden="true"></i> </small>
                                                        <br>
                                                        <a href="{{route('message.show',$message->slug)}}">#{{$message->id}} «{{$message->title}}»</a>
                                                    </p>
                                                </div>
                                                <nav class="level is-mobile">
                                                    <div class="level-left">
                                                        @if(auth()->id())
                                                        <form method="post" name="like-{{$message->id}}" action="/posts/like">
                                                            {{csrf_field()}}
                                                            <input type="hidden" name="id" value="{{$message->id}}">
                                                        </form>
                                                        @else
                                                        @endif
                                                        <a  @if (auth()->id()) onclick="forms['like-{{$message->id}}'].submit();"@else  @endif class="level-item">
                                                            <span class="icon is-small"><i class="fa fa-heart m-r-5"></i><span>{{$message->likepost->count()}}</span></span>
                                                        </a>
                                                    </div>
                                                </nav>
                                            </div>

                                        </article>
                                    @endforeach
                                @else
                                    @include('includes.fail')
                                @endif
                            </div>
                        </div>
                    </div>
                    {{ $posts->links() }}
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
            data() {
                return {
                    activeTab:0,
                }
            }
        });
    </script>
@endsection