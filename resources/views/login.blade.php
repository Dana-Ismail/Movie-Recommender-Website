<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel='stylesheet' href='https://static.fontawesome.com/css/fontawesome-app.css'>
    <link href="https://fonts.googleapis.com/css?family=Cardo:400i|Rubik:400,700&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://pro.fontawesome.com/releases/v5.9.0/css/all.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400&amp;display=swap'>
    <link rel="stylesheet" href="{{ url('c.css') }}">
    <link rel="stylesheet" href="{{ url('navbar.css') }}">
    <link rel="stylesheet" href="{{ url('popular.css') }}">
    <link rel="stylesheet" href="{{ url('login.css') }}">
</head>
<body>
<header>
        <ul class="snip1143">
            <li ><a class="home" href="#" data-hover="Home">Home</a></li>
            <li><a class="movies" href="#" data-hover="Movies">Movies</a></li>
            @guest
            <li class="current" ><a class="profile" href="{{ route('login') }}" data-hover="Profile">Profile</a></li>
            @else
            <li class="current"><a class="profile" href="{{ route('profile') }}" data-hover="Profile">Profile</a></li>
            @endguest
        </ul>
    </header>
    <div class="container">
    <div class="screen">
        <div class="screen__content">
            <form class="login" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="login__field">
                    <i class="login__icon fas fa-user"></i>
                    <input type="email" name="email" class="login__input" placeholder="Email" required>
                </div>
                <div class="login__field">
                    <i class="login__icon fas fa-lock"></i>
                    <input type="password" name="password" class="login__input" placeholder="Password" required>
                </div>
                <button type="submit" class="button login__submit">
                    <span class="button__text">Log In Now</span>
                    <i class="button__icon fas fa-chevron-right"></i>
                </button>
            </form>
        </div>
        <div class="screen__background">
            <span class="screen__background__shape screen__background__shape4"></span>
            <span class="screen__background__shape screen__background__shape3"></span>
            <span class="screen__background__shape screen__background__shape2"></span>
            <span class="screen__background__shape screen__background__shape1"></span>
        </div>
    </div>
</div>

<p>Don't have an account? <a href="{{ route('signup') }}">Sign up</a></p>
</body>
</html>
