<?php
session_start();
if (!isset($_SESSION['client_id'])) { header("Location: index.php"); exit(); }

include 'db.php';

$client_id = $_SESSION['client_id'];
$result = $conn->query("SELECT * FROM appointments WHERE client_id=$client_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Appointment Requests</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
<h1>My Appointments</h1>
<nav><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></nav>
</header>
<main>
<?php if($result->num_rows > 0): ?>
<table>
<tr><th>ID</th><th>Reason</th><th>Status</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['reason']) ?></td>
<td>
<?php
$status_class = strtolower($row['status']);
echo "<span class='status-$status_class'>" . htmlspecialchars($row['status']) . "</span>";
?>
</td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<p>You have no appointment requests yet.</p>
<?php endif; ?>
</main>
</body>
</html>
<?php $conn->close(); ?>
