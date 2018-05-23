@extends('layouts.app')

@section('content')
    <div class="columns m-t-10 m-l-10">
        <div class="column">
            @include('includes.left')
        </div>
        <div class="column is-three-fifths">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Profit</th>
                    <th>Дата создания</th>
                    {{--<th>Действия</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($referrals as $referral)
                    <tr>
                        <th>{{ $referral->referral->id }}</th>
                        <th>{{ $referral->referral->name }}</th>
                        <th>{{ $referral->profit }}</th>
                        <th>{{ $referral->created_at->toFormattedDateString() }}</th>
                        {{--<td class="has-text-right"><a class="button is-outlined is-small m-r-5" href="{{route('users.show', $user->id)}}">View</a><a class="button is-outlined is-small" href="{{route('users.edit', $user->id)}}">Edit</a></td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
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
