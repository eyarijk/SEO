@extends('layouts.app')

@section('content')

@if(session('status'))
  <div class="notification is-success">
    {{ session('status') }}
  </div>
@endif

<div class="columns">
  <div class="column is-one-third is-offset-one-third m-t-100">
    <div class="card">
      <div class="card-content">
        <form class="" action="{{route('password.email')}}" method="post" role="form">
          {{csrf_field()}}
          <h1 class="title">Forgot Password?</h1>
          <div class="field">
            <label for="email" class="label">Email Address</label>
            <p class="control">
              <input type="text" class="input {{$errors->has('email') ? 'is-danger' : ' '}}" name="email" value="{{old('email')}}" id="email" placeholder="name@example.com">
            </p>
            @if($errors->has('email'))
              <p class="help if-danger">{{$errors->first('email')}}</p>
            @endif
          </div>

          <button type="submit" class="button is-primary is-outlined is-fullwidth m-t-30" name="button">Get Reset Link</button>
        </form>
      </div>
    </div>
    <h5 class="has-text-centered m-t-20"> <a href="{{route('login')}}" class="is-muted"> <i class="fa fa-caret-left"></i> Back to Login ?</a> </h5>
  </div>
</div>

@endsection
