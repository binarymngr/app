<!DOCTYPE html>
<html class="login-pf">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>BinaryMngr - @lang('login.title')</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css" charset="utf-8">
    <link rel="stylesheet" href="/components/patternfly/dist/css/patternfly.min.css" charset="utf-8">
    <link rel="stylesheet" href="/application.css" charset="utf-8">
</head>

<body>
    <span id="badge">
        <img src="/img/logo_white.png" height="50" alt="BinaryMngr logo" />
    </span>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div id="brand">
                    <h1>@lang('login.title')</h1>
                </div>
            </div>
            <div class="col-sm-7 col-md-6 col-lg-5 login">
                <form action="/auth/login" method="POST" class="form-horizontal" role="form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="email" class="col-sm-2 col-md-2 control-label">@lang('login.email')</label>
                        <div class="col-sm-10 col-md-10">
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@domain.tld" value="{{ Session::get('email', '') }}" tabindex="1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 col-md-2 control-label">@lang('login.password')</label>
                        <div class="col-sm-10 col-md-10">
                            <input type="password" class="form-control" id="password" name="password" placeholder="******" tabindex="2" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-8 col-sm-offset-2 col-sm-6 col-md-offset-2 col-md-6">
                            <div class="checkbox">
                                <label>
                                    <input tabindex="3" type="checkbox" name="remember"> @lang('login.remember-email')
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 submit">
                            <button type="submit" class="btn btn-primary btn-lg" tabindex="4">@lang('login.login')</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-5 col-md-6 col-lg-7 details">
                <p><strong>@lang('login.welcome')</strong><br>
                    @lang('login.intro')</p>
                    @if (Session::get('login_failed'))
                        <p style="color: red"><br>
                            <span class="pficon-layered">
                                <span class="pficon pficon-error-octagon"></span>
                                <span class="pficon pficon-error-exclamation"></span>
                            </span>
                            @lang('login.invalid-credentials')
                        </p>
                    @elseif (Session::get('logged_out'))
                        <p style="color: #439b3b"><br>
                            <span class="pficon pficon-ok"></span>
                            @lang('login.logged-out')
                        </p>
                    @endif
            </div>
        </div>
    </div>
</body>
</html>
