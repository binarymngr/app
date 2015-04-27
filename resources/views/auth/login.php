<!DOCTYPE html>
<html class="login-pf">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>BinaryMngr - Login</title>
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
                    <h1>Login</h1>
                </div>
            </div>
            <div class="col-sm-7 col-md-6 col-lg-5 login">
                <form action="/auth/login" method="POST" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="email" class="col-sm-2 col-md-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-10">
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@domain.tld" tabindex="1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 col-md-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-10">
                            <input type="password" class="form-control" id="password" name="password" placeholder="******" tabindex="2" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-12 submit">
                            <button type="submit" class="btn btn-primary btn-lg" tabindex="4">Log In</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-5 col-md-6 col-lg-7 details">
                <p><strong>Welcome to BinaryMngr!</strong><br>
                    Enjoy the new way of managing self-compiled binaries.</p>
            </div>
        </div>
    </div>
</body>
</html>
