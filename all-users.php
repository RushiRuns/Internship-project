<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$current_user_id = $_SESSION["user_id"];

// -------- HANDLE DELETE --------
if (isset($_POST["delete_account"])) {
    $target_user_id = $_POST["target_user_id"];
    $stmt = $conn->prepare("DELETE FROM registration WHERE id = ?");
    $stmt->bind_param("i", $target_user_id);
    $stmt->execute();
    $stmt->close();

    // If user deleted themselves, destroy session
    if ($target_user_id == $current_user_id) {
        session_destroy();
        header("Location: registration.php");
        exit();
    }

    header("Location: all-users.php");
    exit();
}

// -------- HANDLE UPDATE --------
if (isset($_POST["update_profile"])) {
    $target_user_id = $_POST["target_user_id"];
    $new_username = htmlspecialchars($_POST["username"]);
    $new_email = htmlspecialchars($_POST["email"]);

    // Check if email is already taken by another user
    $check = $conn->prepare("SELECT id FROM registration WHERE email = ? AND id != ?");
    $check->bind_param("si", $new_email, $target_user_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('That email is already in use.');</script>";
        $check->close();
    } else {
        $check->close();
        $stmt = $conn->prepare("UPDATE registration SET username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $new_username, $new_email, $target_user_id);
        $stmt->execute();
        $stmt->close();

        // If user updated themselves, update session
        if ($target_user_id == $current_user_id) {
            $_SESSION["username"] = $new_username;
            $_SESSION["email"] = $new_email;
        }

        header("Location: all-users.php");
        exit();
    }
}

// -------- FETCH ALL USERS --------
$all_users_result = $conn->query("SELECT id, username, email FROM registration ORDER BY id DESC");
$all_users = $all_users_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/adec19c223.js" crossorigin="anonymous"></script>
    <title>All Users</title>
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
        <h2>All Registered Users</h2>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_users as $u): ?>
                    <tr>
                        <td>
                            <?= $u['id'] ?>
                        </td>
                        <td>
                            <form action="all-users.php" method="POST" class="inline-edit-form">
                                <input type="hidden" name="target_user_id" value="<?= $u['id'] ?>">
                                <input type="text" name="username" value="<?= $u['username'] ?>" required minlength="3"
                                    maxlength="20">
                        </td>
                        <td>
                            <input type="email" name="email" value="<?= $u['email'] ?>" required maxlength="50">
                        </td>
                        <td>
                            <button type="submit" name="update_profile" class="edit-btn">Save</button>
                            </form>
                            <form action="all-users.php" method="POST" style="display:inline;"
                                onsubmit="return confirm('Delete this user permanently?')">
                                <input type="hidden" name="target_user_id" value="<?= $u['id'] ?>">
                                <button type="submit" name="delete_account" class="delete-btn-small">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <script src="script.js"></script>
</body>

</html>