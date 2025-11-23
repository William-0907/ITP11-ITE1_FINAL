<?php
session_start();
if (!isset($_SESSION['client_id'])) { header("Location: index.php"); exit(); }

include 'db.php';

$result = $conn->query("SELECT id, name, specialization FROM doctors ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Available Doctors</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
<h1>Available Doctors</h1>
<nav><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a></nav>
</header>
<main>
<?php if($result->num_rows > 0): ?>
<table>
<tr><th>Name</th><th>Specialization</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= htmlspecialchars($row['specialization']) ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<p>No doctors available at the moment.</p>
<?php endif; ?>
</main>
</body>
</html>
<?php $conn->close(); ?>
