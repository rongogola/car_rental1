<?php
require_once 'dbConfig.php';
$id = $_GET['id'];
$car_stmt = $conn->prepare("SELECT * FROM cars WHERE car_id = ?");
$car_stmt->execute([$id]);
$car = $car_stmt->fetch();

$cat_stmt = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
$categories = $cat_stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("UPDATE cars SET category_id = ?, model_name = ?, license_plate = ?, status = ? WHERE car_id = ?");
    $stmt->execute([$_POST['category_id'], $_POST['model_name'], $_POST['license_plate'], $_POST['status'], $id]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Car</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f3f5f7; margin: 0; padding: 40px; display: flex; justify-content: center; }
        .form-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        h2 { margin-top: 0; color: #2b3a42; border-bottom: 2px solid #f3f5f7; padding-bottom: 10px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        input[type="text"], select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 15px; background-color: white; }
        input:focus, select:focus { border-color: #3f51b5; outline: none; }
        .btn-group { display: flex; gap: 10px; margin-top: 25px; }
        button { background: #ff9f1c; color: #1a1a1a; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: bold; flex: 2; transition: all 0.2s ease; }
        button:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-cancel { background: #6c757d; color: white; text-decoration: none; padding: 12px 20px; border-radius: 4px; text-align: center; font-size: 15px; font-weight: bold; flex: 1; }
        .btn-cancel:hover { background: #5a6268; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>Modify Vehicle Records</h2>
        <form method="POST">
            <div class="form-group">
                <label for="category_id">Category Classification</label>
                <select id="category_id" name="category_id" required>
                    <?php foreach($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>" <?= $c['category_id'] == $car['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="model_name">Vehicle Model Name</label>
                <input type="text" id="model_name" name="model_name" value="<?= htmlspecialchars($car['model_name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="license_plate">License Plate Number</label>
                <input type="text" id="license_plate" name="license_plate" value="<?= htmlspecialchars($car['license_plate']) ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Current Status</label>
                <select id="status" name="status">
                    <option value="Available" <?= $car['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                    <option value="Rented" <?= $car['status'] == 'Rented' ? 'selected' : '' ?>>Rented</option>
                    <option value="Maintenance" <?= $car['status'] == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                </select>
            </div>
            <div class="btn-group">
                <button type="submit">Update Vehicle</button>
                <a href="index.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

</body>
</html>
