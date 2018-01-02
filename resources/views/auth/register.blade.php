@extends('layouts.app')

@section('content')
<div class="columns">
  <div class="column is-one-third is-offset-one-third m-t-100">
    <div class="card">
      <div class="card-content">
        <form class=""  name="form"  action="{{route('register')}}" method="post" role="form">
          {{csrf_field()}}
          <h1 class="title">Join The Community</h1>
          <div class="field">
            <label for="name" class="label">Name</label>
            <p class="control">
              <input type="text" class="input {{$errors->has('email') ? 'is-danger' : ' '}}" name="name" value="{{old('name')}}" id="name" required>
            </p>
            @if($errors->has('name'))
              <p class="help if-danger">{{$errors->first('name')}}</p>
            @endif
          </div>
          <div class="field">
            <label for="email" class="label">Email Address</label>
            <p class="control">
              <input type="text" class="input {{$errors->has('email') ? 'is-danger' : ' '}}" name="email" value="{{old('email')}}" id="email" required>
            </p>
            @if($errors->has('email'))
              <p class="help if-danger">{{$errors->first('email')}}</p>
            @endif
          </div>
          <div class="columns">
            <div class="column">
              <div class="field">
                <label for="password" class="label">Password</label>
                <p class="control">
                  <input type="password" class="input {{$errors->has('password') ? 'is-danger' : ' '}}" name="password" value="" id="password" required>
                </p>
                @if($errors->has('password'))
                  <p class="help if-danger">{{$errors->first('password')}}</p>
                @endif
              </div>
            </div>
            <div class="column">
              <div class="field">
                <label for="password" class="label">Confirm Password</label>
                <p class="control">
                  <input type="password" class="input {{$errors->has('password_confirmation') ? 'is-danger' : ' '}}" name="password_confirmation" value="" id="password" required>
                </p>
                @if($errors->has('password_confirmation'))
                  <p class="help if-danger">{{$errors->first('password_confirmation')}}</p>
                @endif
              </div>
            </div>
          </div>
          <button type="submit" onclick="event.preventDefault();" @click="confirmCustom" class="button is-primary is-outlined is-fullwidth m-t-30" name="button">Register</button>
        </form>
      </div>
    </div>
    <h5 class="has-text-centered m-t-20"> <a href="{{route('login')}}" class="is-muted">Already have an Account?</a> </h5>
  </div>
</div>
@endsection
@section('scripts')
  <script>
      var app = new Vue({
          el: '#app',
          methods: {
              confirmCustom() {
                  this.$dialog.confirm({
                      title: 'Privacy Politics',
                      message: `Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Fusce id fermentum quam. Proin sagittis,
                        nibh id hendrerit imperdiet, elit sapien laoreet elit,
                        ac scelerisque diam velit in nisl. Nunc maximus ex non
                        laoreet semper. Nunc scelerisque, libero sit amet pretium dignissim,
                        augue purus placerat justo,
                        sit amet porttitor dui metus in nisl.
                        Nulla non leo placerat, porta metus eu, laoreet risus.
                        Etiam lacinia, purus eu luctus maximus, elit ex viverra tellus,
                        sit amet sodales quam dui nec odio.
                        Nullam porta mollis est. Quisque aliquet malesuada fringilla.
                        Pellentesque volutpat lacus at ante posuere,
                        non pulvinar ante porta. Proin viverra eu massa nec porta.
                        Aliquam rhoncus velit quis sem hendrerit,
                        ut dictum nisl accumsan. Maecenas erat enim, scelerisque non ligula ac,
                        eleifend venenatis ligula.
                        Praesent molestie mauris sed elit posuere, non malesuada libero gravida.
                        In hac habitasse platea dictumst.
                        Pellentesque habitant morbi tristique senectus
                        et netus et malesuada fames ac turpis egestas.`,
                      cancelText: 'Disagree',
                      confirmText: 'Agree',
                      type: 'is-success',
                      onConfirm: () => document.forms["form"].submit()
                })
              }
          }
      });
  </script>
@endsection