@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @if (auth()->id())
                @include('includes.left')
            @endif
        </div>
        <div class="column is-three-fifths">
            <h5 class="title is-5">{{$post->title}}</h5>
            <div class="box">
                <article class="media">
                    <div class="media-left">
                        <figure class="image is-64x64">
                            <img src="@if ($post->user->avatar != null){{asset('images/user/'.$post->user->avatar)}} @else {{asset('images/icon/avatar.png')}} @endif" alt="Image">
                        </figure>
                    </div>
                    <div class="media-content">
                        <div class="content">
                            <p>
                                <i class="fa fa-user m-r-5" aria-hidden="true"></i><strong>{{$post->user->name}}</strong> <small>{{$post->created_at->diffForHumans()}}</small> <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <br>
                                {!! $post->content !!}
                            </p>
                        </div>
                        <nav class="level is-mobile">
                            <div class="level-left">
                                @if(auth()->id())
                                    <form method="post" name="like-{{$post->id}}" action="/posts/like">
                                        {{csrf_field()}}
                                        <input type="hidden" name="id" value="{{$post->id}}">
                                    </form>
                                @else
                                @endif
                                <a  @if (auth()->id()) onclick="forms['like-{{$post->id}}'].submit();"@else  @endif class="m-l-5 level-item">
                                    <span class="icon is-small"><i class="fa fa-heart m-r-5"></i><span>{{$post->likepost->count()}}</span></span>
                                </a>
                            </div>
                        </nav>
                    </div>
                </article>
            </div>
            <div class="box">
                @if (count($comments) > 0)
                    <h5 class="title is-5">Комментарии:</h5>
                    @foreach ($comments as $comment)
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
                            </div>
                        </article>
                    @endforeach
                    @if ($form == null)
                        <form name="sendcomments" action="{{route('post.comment')}}" method="post">
                            {{csrf_field()}}
                            <hr>
                            <textarea name="description" class="textarea" placeholder="Напишите комментарий..."></textarea>
                            <input type="hidden" name="id" value="{{$post->id}}">
                            <br>
                            <a onclick="forms['sendcomments'].submit();" class="button is-success">Добавить</a>
                        </form>
                    @endif
                @else
                    <form name="sendcomments" action="{{route('post.comment')}}" method="post">
                        {{csrf_field()}}
                        <h5 class="title is-5">Напишите первый комментарий.</h5>
                        <hr>
                        <textarea name="description" class="textarea" placeholder="Напишите комментарий..."></textarea>
                        <input type="hidden" name="id" value="{{$post->id}}">
                        <br>
                        <a onclick="forms['sendcomments'].submit();" class="button is-success">Добавить</a>
                    </form>
                @endif
            </div>
            {{$comments->links()}}
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
            data:{}
        });
    </script>
@endsection
