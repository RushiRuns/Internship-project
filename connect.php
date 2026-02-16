<?php
include 'db.php';

$username = htmlspecialchars($_POST["username"]);
$email = htmlspecialchars($_POST["email"]);
$password = $_POST["password"];
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$check = $conn->prepare("SELECT id FROM registration WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "This email is already registered. Please log in.";
} else {
    $stmt = $conn->prepare("INSERT INTO registration (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();
    echo "Registration successful! <a href='login.php'>Login here</a>";
    $stmt->close();
}
$check->close();
$conn->close();
?>