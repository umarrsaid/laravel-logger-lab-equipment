<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Logger | Login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/css/components-md.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('assets/global/css/plugins-md.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/pages/css/login.min.css')}}" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="{{asset('labwebicon.ico')}}" /> </head>
    <!-- END HEAD -->
        <style>
            .login .content .form-actions .btn {
                margin-top: 1px;
                font-weight: 600;
                padding: 10px 150px!important;
            }

            .login .content .form-actions {
                clear: both;
                border: 0;
                border-bottom: 0px solid #eee;
                padding: 25px 30px;
                margin-left: -30px;
                margin-right: -30px;
            }
        </style>

    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="javascript:;">
                <img src="{{asset('assets/layouts/layout4/img/labweb.png')}}" alt="" style="width:400px;margin-top: -40px;margin-bottom: -25px;" /> </a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                @csrf
                <h3 class="form-title font-green">Sign In</h3>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label">Email</label>
                    <input autocomplete="off" placeholder="Email" id="email" type="email" class="form-control-solid placeholder-no-fix form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required />
                    <!-- @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif -->
                </div>
                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input autocomplete="off" placeholder="Password" name="password" id="password" type="password" class="form-control-solid placeholder-no-fix form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required />
                </div>

                @if ($errors->has('password') || $errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>The email address or password you entered is incorrect</strong>
                    </span>
                @endif
                <div class="form-actions">
                    <button type="submit" class="btn green uppercase">{{ __('Login') }}</button>
                </div>
            </form>
            <!-- END LOGIN FORM -->
        </div>
        <div class="copyright"> 2019 - 2020 &copy; Lab Equipment Data Logging By Solusi Teknis Bandung </div>
        <!--[if lt IE 9]>
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{asset('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
    </body>

</html>
