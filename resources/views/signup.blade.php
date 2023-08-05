<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel='stylesheet' href='https://static.fontawesome.com/css/fontawesome-app.css'>
    <link href="https://fonts.googleapis.com/css?family=Cardo:400i|Rubik:400,700&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://pro.fontawesome.com/releases/v5.9.0/css/all.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400&amp;display=swap'>
   
    <link rel="stylesheet" href="{{ url('login.css') }}">
</head>
<body>

<div class="wrapper">

  <div class="cont">
    <div class="centered-content">
      <a href="/" class="triangle-container navbar-brand triangle-single">
        <div class="triangle-wrapper">
          <div class="triangle triangle-single"></div>
        </div>
      </a>
    </div>

    <div class="centered-content">
      <form class="form login" action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="login__field">
          <input type="name" name="name" class="login__input" placeholder="Name" required>
        </div>
        <div class="login__field">
          <input type="email" name="email" class="login__input" placeholder="Email" required>
        </div>
        <div class="login__field">
          <input type="password" name="password" class="login__input" placeholder="Password" required>
        </div>
        <button  type="submit" class="button login__submit">
          <span class="button__text">SignUp</span>
          <i class="button__icon fas fa-chevron-right"></i>
        </button>
      </form>

      <p style="color:white; text-align:center;">Have An Account? <a style="outline:none; color:greenyellow" href="{{ route('login') }}">LogIn</a></p>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ url('logo.js') }}"></script>
<script src="{{ url('signup.js') }}"></script>
</body>
</html>
