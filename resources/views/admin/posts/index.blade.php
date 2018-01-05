@extends('layouts.manage')

@section('content')

<div class="flex-container">
  <div class="columns m-t-10">
    <div class="column">
      <h1 class="title">Здесь все статьи ({{$posts->count()}})</h1>
    </div>
    <div class="column">
      <a href="{{route('posts.create')}}" class="button is-primary is-pulled-right"><i class="fa fa-file-o  m-r-10"></i>Создать статью</a>
    </div>
  </div>
    <hr class="m-t-0">
    @if (count($posts) > 0)
      @foreach($posts as $post)
        <div class="card m-b-10">
          <header class="card-header">
            <p class="card-header-title">
              #{{$post->id}}. {{$post->title}}
            </p>
          </header>
          <div class="card-content">
            <div class="content">
              {{str_limit($post->content,250)}}
              <br>
              <time>Создано: {{$post->created_at->diffForHumans()}}</time>
            </div>
          </div>
          <footer class="card-footer">
              <form name="status-{{$post->id}}" action="/admin/posts/status" method="post">
                  {{csrf_field()}}
                  <input type="hidden" name="id" value="{{$post->id}}">
              </form>
              <form name="delete-{{$post->id}}" action="{{route('posts.destroy',$post->id)}}" method="post">
                  {{csrf_field()}}
                  {{method_field('delete')}}
                  <input type="hidden" name="id" value="{{$post->id}}">
              </form>
              @if ($post->is_show == false)
                <a onclick="forms['status-{{$post->id}}'].submit();" class="card-footer-item"><i class="fa fa-play m-r-5" aria-hidden="true"></i><span>Опубликовать</span></a>
              @else
                  <a onclick="forms['status-{{$post->id}}'].submit();" class="card-footer-item"><i class="fa fa-pause m-r-5" aria-hidden="true"></i><span>Скрыть</span></a>
              @endif
                  <a href="#" class="card-footer-item"><i class="fa fa-pencil m-r-5" aria-hidden="true"></i><span>Редактировать</span></a>
            <a onclick="forms['delete-{{$post->id}}'].submit();" class="card-footer-item"><i class="fa fa-trash m-r-5" aria-hidden="true"></i><span>Удалить</span></a>
          </footer>
        </div>
      @endforeach
    @else
        @include('includes.fail')
    @endif
    {{$posts->links()}}
</div>

@endsection
