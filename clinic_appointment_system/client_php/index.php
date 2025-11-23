<?php
session_start();
include 'db.php'; 

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM clients WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hash);
    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['client_id'] = $id;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid username or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title></title>
<link rel="stylesheet" href="auth_style.css">
</head>
<body>
<div class="container">

<h1>Clinic Appointment Portal</h1>
<h2>Client Login</h2>

<?php if($message): ?>
    <ul>
        <li><?php echo $message; ?></li>
    </ul>
<?php endif; ?>

<form method="POST">
    <input class="form-input" type="text" name="username" placeholder="Username" required>
    <input class="form-input" type="password" name="password" placeholder="Password" required>
    <button class="btn" type="submit">Login</button>
</form>


<p class="toggle-text">
    Don’t have an account?
    <a href="register.php">Register</a>
</p>

<p class="toggle-text">
    Login as Staff
    <a href="http://127.0.0.1:8000/staff/login/">Go to Staff Login</a>
</p>

</div>
</body>
</html>
