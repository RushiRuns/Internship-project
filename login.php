<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login page</title>
</head>

<body>
    <section class="form-container form-container-layout border-rounded">
        <h2 class="form-container-heading text-center">Login Page</h2>
        <p class="form-container-text text-center">Welcome back user.</p>
        <form class="form" action="retreive.php" method="POST" autocomplete="off">
            <label for="email">Email</label>
            <input class="form-input border-rounded" type="email" name="email" id="email"
                placeholder="johndoe@gmail.com" required maxlength="50">
            <label for="password">Password</label>
            <input class="form-input border-rounded" type="password" name="password" id="password" required
                minlength="8" maxlength="20">
            <button class="btn btn--form" type="submit" name="login_btn">Login</button>

        </form>
        <a href="registration.php" class="form-link text-center">New user? Register here</a>
    </section>
</body>

</html>