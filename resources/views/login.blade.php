<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="admin login">
    <title>Admin - {{ Omega::setting("title") }}</title>
    <link rel="stylesheet" href="{{ config('omega.assets_path') }}/lib/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ config('omega.assets_path') }}/css/login.css">
    <style>
        body {
            background-image:url('{{ Omega::image( Omega::setting("admin_bg_image"), config('omega.assets_path') . "/images/bg.jpg" ) }}');
        }
        .login-sidebar:after {
            background: linear-gradient(-135deg, {{config('omega.login.gradient_a','#ffffff')}}, {{config('omega.login.gradient_b','#ffffff')}});
            background: -webkit-linear-gradient(-135deg, {{config('omega.login.gradient_a','#ffffff')}}, {{config('omega.login.gradient_b','#ffffff')}});
        }
        .login-button, .bar:before, .bar:after{
            background:{{ config('omega.primary_color','#22A7F0') }};
        }

    </style>

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
<!-- Designed with ♥ by Frondor -->
<div class="container-fluid">
    <div class="row">
        <div class="faded-bg"></div>
        <div class="hidden-xs col-sm-8 col-md-9">
            <div class="clearfix">
                <div class="col-sm-12 col-md-10 col-md-offset-2">
                    <div class="logo-title-container">
                        <?php $admin_logo_img = Omega::setting('admin_icon_image', ''); ?>
                        @if($admin_logo_img == '')
                        <img class="img-responsive pull-left logo hidden-xs" src="{{ config('omega.assets_path') }}/images/logo-icon-light.png" alt="Logo Icon">
                        @else
                        <img class="img-responsive pull-left logo hidden-xs" src="{{ Omega::image($admin_logo_img) }}" alt="Logo Icon">
                        @endif
                        <div class="copy">
                            <h1>{{ Omega::setting('admin_title', 'OmegaCP') }}</h1>
                            <p>{{ Omega::setting('admin_description', 'Welcome to Omega Rangarok Admin Panel. The Missing Admin for Ragnarok Online') }}</p>
                        </div>
                    </div> <!-- .logo-title-container -->
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-3 login-sidebar">

            <div class="login-container">

                <h2>Sign In Below:</h2>

                <form action="{{ route('omega.login') }}" method="POST">
                {{ csrf_field() }}
                <div class="group">      
                  <input type="text" name="email" value="{{ old('email') }}" required>
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label><i class="glyphicon glyphicon-user"></i><span class="span-input"> E-mail</span></label>
                </div>

                <div class="group">      
                  <input type="password" name="password" required>
                  <span class="highlight"></span>
                  <span class="bar"></span>
                  <label><i class="glyphicon glyphicon-lock"></i><span class="span-input"> Password</span></label>
                </div>

                <button type="submit" class="btn btn-block login-button">
                    <span class="signingin hidden"><span class="glyphicon glyphicon-refresh"></span> Loggin in...</span>
                    <span class="signin">Login</span>
                </button>
               
              </form>

              @if(!$errors->isEmpty())
              <div class="alert alert-black">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach                
                </ul>
              </div>            
              @endif

            </div> <!-- .login-container -->
            
        </div> <!-- .login-sidebar -->
    </div> <!-- .row -->
</div> <!-- .container-fluid -->
<script>
    var btn = document.querySelector('button[type="submit"]');
    var form = document.forms[0];
    btn.addEventListener('click', function(ev){
        if (form.checkValidity()) {
            btn.querySelector('.signingin').className = 'signingin';
            btn.querySelector('.signin').className = 'signin hidden';
        } else {
            ev.preventDefault();
        }
    });
</script>
</body>
</html>
