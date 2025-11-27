<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: index.php");
    exit();
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $reason = $_POST['reason'];

    // Add created_at using NOW()
    $stmt = $conn->prepare("INSERT INTO appointments (client_id, doctor_id, reason, status, created_at) VALUES (?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("iis", $_SESSION['client_id'], $doctor_id, $reason);
    $stmt->execute();
    $stmt->close();

    $message = "Appointment request submitted!";
}

$client_id = $_SESSION['client_id'];
$appointments = $conn->query("
    SELECT a.id, a.reason, a.status, a.appointment_date, a.appointment_time,
           d.name AS doctor_name
    FROM appointments a
    LEFT JOIN doctors d ON a.doctor_id = d.id
    WHERE a.client_id = $client_id
    ORDER BY a.id DESC
");

$doctors_result = $conn->query("SELECT id, name, specialization FROM doctors ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Client Dashboard | Clinic Appointment</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Client Dashboard</h1>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="my_requests.php">My Requests</a>
        <a href="doctors.php">Doctors</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
<?php if(isset($message)): ?>
    <div class="status-approved"><?= $message ?></div>
<?php endif; ?>

<h2>Request a New Appointment</h2>
<form method="POST">
    <label for="doctor_id">Select Doctor:</label>
    <select name="doctor_id" required>
        <option value="">--Choose a doctor--</option>
        <?php while($doc = $doctors_result->fetch_assoc()): ?>
            <option value="<?= $doc['id'] ?>"><?= htmlspecialchars($doc['name']) ?> (<?= htmlspecialchars($doc['specialization']) ?>)</option>
        <?php endwhile; ?>
    </select>

    <label for="reason">Reason for Appointment:</label>
    <textarea name="reason" rows="4" required></textarea>
    <button class="btn" type="submit">Submit Request</button>
</form>

<h2>Your Appointments</h2>
<table>
    <thead>
        <tr>
            <th>Doctor</th>
            <th>Reason</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if($appointments->num_rows > 0): ?>
            <?php while($row = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['doctor_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['reason']) ?></td>
                    <td><?= htmlspecialchars($row['appointment_date'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['appointment_time'] ?? '-') ?></td>
                    <td><span class="status-<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No appointments yet.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</main>

<footer>
    Clinic Appointment Portal © 2025
</footer>

</body>
</html>
<?php $conn->close(); ?>
