@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column is-three-fifths">
            <b-tabs v-model="activeTab">
                <b-tab-item label="{{$title}}">
                    <div class="columns">
                        <div class="column">
                            <div class="box">
                                @if (count($tasks) > 0)
                                    @foreach ($tasks as $task)
                                        <article class="media">
                                            <div class="media-left">
                                                <figure class="image is-64x64">
                                                    <img src="{{ asset('images/icon/fail.png') }}" alt="Image">
                                                </figure>
                                            </div>
                                            <div class="media-content">
                                                <div class="content">
                                                    <p class="task">
                                                        <i class="fa fa-user" aria-hidden="true"></i><a href="{{route('tasks.show',$task->slug)}}"> {{$task->user->name}}</a> <small>{{$task->updated_at->diffForHumans()}} <i class="fa fa-clock-o" aria-hidden="true"></i> </small>
                                                        <br>
                                                        <a href="{{route('tasks.show',$task->slug)}}">{{$task->name}}</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="media-right" style="margin-top: 20px; font-size: 20px;">
                                                <i class="fa fa-money" aria-hidden="true"></i> {{money_format('%i',$task->salary)}} ₽
                                            </div>
                                        </article>
                                    @endforeach
                                @else
                                    @include('includes.fail')
                                @endif
                            </div>
                        </div>
                    </div>
                    {{ $tasks->links() }}
                </b-tab-item>
            </b-tabs>
        </div>
        <div class="column">
            @include('includes.right')
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