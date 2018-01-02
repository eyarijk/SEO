@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column is-three-fifths">
            <b-tabs>
                <b-tab-item label="Письмо: {{$message->name}}">
                    <div class="columns">
                        <div class="column">
                            <div class="box">
                                @if (count($message->comments) > 0)
                                    @foreach ($message->comments as $comment)
                                        <article class="media">
                                            <div class="media-left">
                                                <figure class="image is-64x64">
                                                    <img style="border-radius: 50%"
                                                         @if ($comment->user->avatar != null)
                                                         src="{{asset('images/user/'.$comment->user->avatar)}}"
                                                         @else
                                                         src="{{asset('images/icon/avatar.png')}}"
                                                         @endif
                                                         alt="Image">
                                                </figure>
                                            </div>
                                            <div class="media-content">
                                                @if ($comment->user_id == auth()->id())
                                                    <form action="{{route('comments.destroy',$comment->id)}}" method="post">
                                                        {{csrf_field()}}
                                                        {{method_field('delete')}}
                                                        <button style="float: right; top:-4px;" class="delete m-b-10"></button>
                                                    </form>
                                                @endif()
                                                <div class="content">
                                                    <p>
                                                        <strong>{{$comment->user->name}}</strong> <small>{{$comment->created_at->diffForHumans()}}</small>
                                                        <br>
                                                        {{$comment->description}}
                                                    </p>

                                                </div>
                                                <nav class="level is-mobile">
                                                    <div class="level-left">
                                                        <a class="level-item">
                                                            <span class="icon is-small"><i class="fa fa-reply"></i></span>
                                                        </a>
                                                        <a class="level-item">
                                                            <span class="icon is-small"><i class="fa fa-heart"></i></span>
                                                        </a>
                                                    </div>
                                                </nav>
                                            </div>
                                        </article>
                                    @endforeach
                                    @if ($form == null)
                                        <form name="sendcomments" action="/message/comment/create" method="post">
                                            {{csrf_field()}}
                                            <hr>
                                            <textarea name="description" class="textarea" placeholder="Напишите отзыв..."></textarea>
                                            <input type="hidden" name="id" value="{{$message->id}}">
                                            <br>
                                            <a onclick="forms['sendcomments'].submit();" class="button is-success">Добавить</a>
                                        </form>
                                    @endif
                                @else
                                    <form name="sendcomments" action="/message/comment/create" method="post">
                                        {{csrf_field()}}
                                        <h5 class="title is-5">Напишите первый отзыв.</h5>
                                        <hr>
                                        <textarea name="description" class="textarea" placeholder="Напишите отзыв..."></textarea>
                                        <input type="hidden" name="id" value="{{$message->id}}">
                                        <br>
                                        <a onclick="forms['sendcomments'].submit();" class="button is-success">Добавить</a>
                                    </form>
                                @endif
                            </div>
                        </div>
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
            data: {}
        });
    </script>
@endsection