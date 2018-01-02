@extends('layouts.app')

@section('content')

  <div class="columns">
    <div class="column is-one-third is-offset-one-third m-t-100">
      <div class="card">
        <div class="card-content">
          <form class="" action="{{route('login')}}" method="post" role="form">
            {{csrf_field()}}
            <h1 class="title">Log In</h1>
            <div class="field">
              <label for="email" class="label">Email Address</label>
              <p class="control">
                <input type="text" class="input {{$errors->has('email') ? 'is-danger' : ' '}}" name="email" value="{{old('email')}}" id="email" placeholder="name@example.com">
              </p>
              @if($errors->has('email'))
                <p class="help if-danger">{{$errors->first('email')}}</p>
              @endif
            </div>
            <div class="field">
              <label for="password" class="label">Password</label>
              <p class="control">
                <input type="password" class="input {{$errors->has('password') ? 'is-danger' : ' '}}" name="password" value="" id="password">
              </p>
              @if($errors->has('password'))
                <p class="help if-danger">{{$errors->first('password')}}</p>
              @endif
            </div>
            <b-checkbox name="remember" class="m-t-10" {{ old('remember') ? 'checked' : '' }}>Remember Me</b-checkbox>
            <button type="submit"  class="button is-primary is-outlined is-fullwidth m-t-30"  name="button">Log in</button>
          </form>
        </div>
      </div>
      <h5 class="has-text-centered m-t-20"> <a href="{{route('password.request')}}" class="is-muted">Forgout Your Password?</a> </h5>
    </div>
  </div>

@endsection
@section('scripts')
  <script>
      var app = new Vue({
          el: '#app',
      });
  </script>
@endsection
