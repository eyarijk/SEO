@extends('layouts.manage')

@section('content')
    <div class="flex-container">
        <div class="columns m-t-10">
            <div class="column ">
                <h1 class="title">Управление категориями</h1>
            </div>
            <div class="column">
                <a href="{{route('category.create')}}" class="button is-primary is-pulled-right"><i class="fa fa-user-plus m-r-10"></i>Создать категорию</a>
            </div>
        </div>
        <hr class="m-t-0">
        <div class="card">
            <div class="card-content">
                <table class="table is-narrow">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Slug</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <th>{{$category->id}}</th>
                            <th>{{$category->name}}</th>
                            <th>{{$category->slug}}</th>
                            <th>{{$category->created_at->toFormattedDateString()}}</th>
                            <td class="has-text-right"><a class="button is-outlined is-small" href="{{route('category.edit', $category->id)}}">Edit</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        {{$categories->links()}}
    </div>


@endsection
