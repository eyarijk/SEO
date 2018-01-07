<!DOCTYPE html>
<!--[if lte IE 9]><html class="no-js is-ie"><![endif]-->
<!--[if gt IE 8]><!--><html class=no-js><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>SEO</title>
    <link rel=stylesheet href="{{ asset('welcome/css/main.css') }}">
    <!--[if lte IE 8]>
    <link rel=stylesheet href="{{ asset('welcome/css/ie.css') }}">
    <![endif]-->
    <script src="{{ asset('welcome/js/vendor/modernizr.js') }}"></script>
    <script src="{{ asset('welcome/js/vendor/respond.min.js') }}"></script>

</head>

<body>
<div class="level level-hero hero-home has-hint">


    <div class="hero-overlay visible-lg"></div>

    <video loop id=bg-video class=fullscreen-video>
        <source src="{{ asset('welcome/video/appi.webm') }}" type="video/webm">
        <source src="{{ asset('welcome/video/appi.mp4') }}" type="video/mp4">

    </video>

    <div class="container full-height">
        <div class=v-align-parent>
            <div class=v-center>
                <div class="row">
                    <div class="col-xs-10 col-sm-6">
                        <h1 class="mb-10 heading">ДОБРО ПОЖАЛОВАТЬ</h1>
                        <div class="subheading mb-20">У нас вы сможете легко заработать реаль­ные деньги. <br class=hidden-xs>Для этого Вам не потребуются какие-либо особые навыки или уйма времени. </div>
                    </div>
                </div>
                <div>
                    <a class="btn btn-prepend btn-launch-video" href="/login">
                        <i class="prepended icon-user"></i>Вход
                    </a>
                    <a class="btn btn-prepend" href="/register">
                        <i class="prepended icon-append-play"></i>Регистрация
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="{{ asset('welcome/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('welcome/js/main.js') }}"></script>
<!-- //-end- concat_js -->
</body>
</html>
