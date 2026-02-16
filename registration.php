<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registration page</title>
</head>

<body>
    <section class="form-container form-container-layout border-rounded">
        <h2 class="form-container-heading text-center">Registration Page</h2>
        <p class="form-container-text text-center">Thank you registering.</p>
        <form class="form" action="connect.php" method="POST" autocomplete="off">

            <label for="username">Username</label>
            <input class="form-input border-rounded" type="text" name="username" id="username" placeholder="johndoe"
                required minlength="3" maxlength="20">
            <label for="email">Email</label>
            <input class="form-input border-rounded" type="email" name="email" id="email"
                placeholder="johndoe@gmail.com" required maxlength="50">
            <label for="password">Password</label>
            <input class="form-input border-rounded" type="password" name="password" id="password" required
                minlength="8" maxlength="20">
            <button class="btn btn--form" type="submit" name="register_btn">Register</button>

        </form>
        <a href="login.php" class="form-link text-center">Already have a account? Click here</a>
    </section>
</body>

</html>