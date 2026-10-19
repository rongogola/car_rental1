<?php
require_once 'dbConfig.php';
if (isset($_GET['id'])) {
    $stmt = $conn->prepare("DELETE FROM cars WHERE car_id = ?");
    $stmt->execute([$_GET['id']]);
}
header("Location: index.php");
exit;
?>
