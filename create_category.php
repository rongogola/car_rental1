<?php
require_once 'dbConfig.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO categories (category_name, base_price_per_day) VALUES (:name, :price)");
    $stmt->execute([':name' => $_POST['category_name'], ':price' => $_POST['base_price_per_day']]);
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f3f5f7; margin: 0; padding: 40px; display: flex; justify-content: center; }
        .form-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { margin-top: 0; color: #2b3a42; border-bottom: 2px solid #f3f5f7; padding-bottom: 10px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 15px; }
        input:focus { border-color: #3f51b5; outline: none; }
        .btn-group { display: flex; gap: 10px; margin-top: 25px; }
        button { background: #3f51b5; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; font-size: 15px; font-weight: bold; flex: 2; }
        button:hover { background: #303f9f; }
        .btn-cancel { background: #6c757d; color: white; text-decoration: none; padding: 12px 20px; border-radius: 4px; text-align: center; font-size: 15px; font-weight: bold; flex: 1; }
        .btn-cancel:hover { background: #5a6268; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>Add Vehicle Category</h2>
        <form method="POST">
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" id="category_name" name="category_name" placeholder="e.g., Luxury Sedan, SUV, Compact" required>
            </div>
            <div class="form-group">
                <label for="base_price_per_day">Daily Rental Rate (PHP)</label>
                <input type="number" id="base_price_per_day" step="0.01" name="base_price_per_day" placeholder="e.g., 3500" required>
            </div>
            <div class="btn-group">
                <button type="submit">Save Category</button>
                <a href="index.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

</body>
</html>
