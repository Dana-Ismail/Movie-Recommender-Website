<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn Page</title>
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
      <form class="form login" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="login__field">
          <input type="email" name="email" class="login__input" placeholder="Email" required>
        </div>
        <div class="login__field">
          <input type="password" name="password" class="login__input" placeholder="Password" required>
        </div>
        <button id="login-button" type="submit" class="button login__submit">
          <span class="button__text">LogIn</span>
          <i class="button__icon fas fa-chevron-right"></i>
        </button>
      </form>

      <p style="color:white; text-align:center;">Don't have an account? <a style="outline:none; color:greenyellow" href="{{ route('signup') }}">Sign up</a></p>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Custom Login Script -->
<script>
  $(document).ready(function() {
    // Handle form submission when the login button is clicked
    $("#login-button").click(function(event) {
      event.preventDefault();

      // Submit the login form
      $('form.login').submit();
    });
  });
</script>
<script src="{{ url('logo.js') }}"></script>
</body>
</html>
