<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION["user_id"];

// -------- HANDLE DELETE --------
if (isset($_POST["delete_account"])) {
    $stmt = $conn->prepare("DELETE FROM registration WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    session_destroy();
    header("Location: registration.php");
    exit();
}

// -------- HANDLE UPDATE --------
if (isset($_POST["update_profile"])) {
    $new_username = htmlspecialchars($_POST["username"]);
    $new_email = htmlspecialchars($_POST["email"]);

    // Check if email is already taken
    $check = $conn->prepare("SELECT id FROM registration WHERE email = ? AND id != ?");
    $check->bind_param("si", $new_email, $user_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('That email is already in use.');</script>";
        $check->close();
    } else {
        $check->close();
        $stmt = $conn->prepare("UPDATE registration SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION["username"] = $new_username;
        $_SESSION["email"] = $new_email;

        header("Location: dashboard.php");
        exit();
    }
}

// -------- FETCH LOGGED-IN USER DATA --------
$stmt = $conn->prepare("SELECT username, email FROM registration WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$username = $user['username'];
$email = $user['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/adec19c223.js" crossorigin="anonymous"></script>
    <title>Dashboard</title>
</head>

<body class="dashboard-container">

    <nav>
        <div class="nav border">
            <div class="nav-logo">
                <i class="fa-solid fa-bars menu-icon"></i>
                <a href="#" class="logo">Dashboard</a>
            </div>
            <div class="nav-input">
                <i class="fa-solid fa-magnifying-glass icon icon-mg"></i>
                <input type="text" class="search-input border" placeholder="Search...">
            </div>
        </div>
        <section class="sidebar">
            <div class="sidebar-title">
                <h3 class="title-heading">Dashboard</h3>
                <i class="fa-solid fa-xmark close-icon"></i>
            </div>
            <ul class="sidebar-list">
                <li class="sidebar-items"><a href="dashboard.php">Individual</a></li>
                <li class="sidebar-items"><a href="all-users.php">All users</a></li>
                <li class="sidebar-items"><a href="logout.php">Logout</a></li>
            </ul>
        </section>
    </nav>

    <main class="profile-container">
        <div class="user-profile">
            <img src="profile.jpg" alt="Profile Photo" class="profile-img">
            <div>
                <p class="profile-username"><?= $username ?></p>
                <p class="profile-email"><?= $email ?></p>
            </div>
        </div>
        <hr>
        <div class="update-section">
            <h3>Update Profile</h3>
            <form action="dashboard.php" method="POST" class="update-form">
                <label for="username">Username</label>
                <input class="border border-rounded" type="text" name="username" value="<?= $username ?>" required
                    minlength="3" maxlength="20">

                <label for="email">Email</label>
                <input class="border border-rounded" type="email" name="email" value="<?= $email ?>" required
                    maxlength="50">

                <button type="submit" name="update_profile" class="update-btn">Update</button>
            </form>
        </div>
        <hr>
        <div class="delete-section">
            <h3>Danger Zone</h3>
            <form action="dashboard.php" method="POST" class="delete-form"
                onsubmit="return confirm('Are you sure? This will permanently delete your account.')">
                <button type="submit" name="delete_account" class="delete-btn">Delete My Account</button>
            </form>
        </div>
    </main>

    <script src="script.js"></script>
</body>

</html>