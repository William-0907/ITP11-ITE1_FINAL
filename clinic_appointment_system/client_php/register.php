<?php
session_start();
include 'db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {

        // hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s'); // REQUIRED for your table

        // insert with created_at
        $stmt = $conn->prepare("
            INSERT INTO clients (username, password, created_at)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sss", $username, $hashed_password, $created_at);

        try {
            if ($stmt->execute()) {
                header("Location: index.php"); // redirect to login
                exit();
            }
        } catch (mysqli_sql_exception $e) {

            // duplicate username
            if ($e->getCode() == 1062) {
                $message = "Username already exists. Please choose another.";
            } else {
                $message = "An error occurred while creating the account.";
            }
        }

        $stmt->close();
    }
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
<h2>Register</h2>

<?php if($message): ?>
    <ul>
        <li><?php echo $message; ?></li>
    </ul>
<?php endif; ?>

<form method="POST">
    <input class="form-input" type="text" name="username" placeholder="Username" required>
    <input class="form-input" type="password" name="password" placeholder="Password" required>
    <input class="form-input" type="password" name="confirm_password" placeholder="Confirm Password" required>
    <button class="btn" type="submit">Register</button>
</form>

<p class="toggle-text">
    Already have an account?
    <a href="index.php">Login</a>
</p>

<p class="toggle-text">
    Login as Staff
    <a href="staff_login.php">Go to Staff Login</a>
</p>

</div>
</body>
</html>
