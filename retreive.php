<?php
include 'db.php';

$email = htmlspecialchars($_POST["email"]);
$password = $_POST["password"];


$stmt = $conn->prepare("SELECT id, password FROM registration WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION["user_id"] = $user['id'];
        $_SESSION["username"] = $user['username'];
        $_SESSION["email"] = $email;

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid password. <a href='login.php'>Try again</a>";
    }
} else {
    echo "No account found with this email. <a href='registration.php'>Register</a>";
}
$stmt->close();
$conn->close();
?>