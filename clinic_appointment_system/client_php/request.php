<?php
session_start();
include "db.php";

if (!isset($_SESSION["client_id"])) {
    header("Location: login.php");
    exit();
}

if ($_POST) {
    $client_id = $_SESSION["client_id"];
    $doctor_id = $_POST["doctor_id"];
    $reason = $_POST["reason"];

    $sql = "INSERT INTO appointments (client_id, doctor_id, reason)
            VALUES ('$client_id', '$doctor_id', '$reason')";

    if ($conn->query($sql)) {
        echo "Appointment requested!";
    }
}
?>

<form method="POST">
    Doctor ID: <input name="doctor_id"><br>
    Reason:<br>
    <textarea name="reason"></textarea><br>
    <button>Submit</button>
</form>
