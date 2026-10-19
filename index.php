<?php
require_once 'dbConfig.php';

// Fetch Categories
$cat_stmt = $conn->prepare("SELECT * FROM categories ORDER BY category_id DESC");
$cat_stmt->execute();
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Cars joined with Categories
$car_stmt = $conn->prepare("SELECT cars.*, categories.category_name FROM cars JOIN categories ON cars.category_id = categories.category_id ORDER BY cars.car_id DESC");
$car_stmt->execute();
$cars = $car_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Car Rental Agency Dashboard</title>
    <style>
        :root {
            --primary: #2b3a42;
            --accent: #3f51b5;
            --success: #2ec4b6;
            --warning: #ff9f1c;
            --danger: #e71d36;
            --light: #f8f9fa;
            --dark: #1a1a1a;
        }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: #f3f5f7; 
            color: var(--dark);
        }
        .navbar {
            background-color: var(--primary);
            color: white;
            padding: 15px 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .navbar h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .container { padding: 30px; max-width: 1200px; margin: 0 auto; }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 25px;
            margin-bottom: 40px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--light);
            padding-bottom: 10px;
        }
        .card-header h2 { margin: 0; font-size: 20px; color: var(--primary); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: white; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background-color: var(--light); font-weight: 600; color: #555; }
        tr:hover { background-color: #fafafa; }
        .btn { 
            padding: 8px 16px; 
            text-decoration: none; 
            border-radius: 5px; 
            color: white; 
            font-size: 14px; 
            font-weight: 500; 
            display: inline-block;
            transition: all 0.2s ease;
        }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-add { background-color: var(--accent); }
        .btn-edit { background-color: var(--warning); color: var(--dark); margin-right: 5px; }
        .btn-delete { background-color: var(--danger); }
        .badge {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-available { background-color: #e2f9f5; color: #107c67; }
        .badge-rented { background-color: #fff4e5; color: #b26200; }
        .badge-maintenance { background-color: #ffe5e5; color: #cc0000; }
        .no-data { text-align: center; color: #888; padding: 30px; }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>🚗 Car Rental Agency Management System</h1>
    </div>

    <div class="container">

        <!-- PARENT ENTITY SECTION -->
        <div class="card">
            <div class="card-header">
                <h2>1. Vehicle Categories (Parent Entity)</h2>
                <a href="create_category.php" class="btn btn-add">+ Add New Category</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 50%;">Category Name</th>
                        <th style="width: 20%;">Daily Rate</th>
                        <th style="width: 20%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($categories) > 0): ?>
                        <?php foreach($categories as $c): ?>
                        <tr>
                            <td><?= $c['category_id'] ?></td>
                            <td><strong><?= htmlspecialchars($c['category_name']) ?></strong></td>
                            <td style="color: #2e7d32; font-weight: 600;">₱<?= number_format($c['base_price_per_day'], 2) ?></td>
                            <td>
                                <a href="edit_category.php?id=<?= $c['category_id'] ?>" class="btn btn-edit">Edit</a>
                                <a href="delete_category.php?id=<?= $c['category_id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this category? This will also remove all vehicles inside this category.')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="no-data">No vehicle categories found. Click add to register one.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- CHILD ENTITY SECTION -->
        <div class="card">
            <div class="card-header">
                <h2>2. Fleet Inventory (Child Entity)</h2>
                <a href="create_car.php" class="btn btn-add">+ Register New Car</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Car ID</th>
                        <th>Model Name</th>
                        <th>Classification</th>
                        <th>Plate Number</th>
                        <th>Availability Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($cars) > 0): ?>
                        <?php foreach($cars as $car): ?>
                        <tr>
                            <td><?= $car['car_id'] ?></td>
                            <td><strong><?= htmlspecialchars($car['model_name']) ?></strong></td>
                            <td><span style="background: #eef1f6; padding: 4px 8px; border-radius: 4px; font-size: 13px; font-weight: 500;"><?= htmlspecialchars($car['category_name']) ?></span></td>
                            <td style="font-family: monospace; font-size: 15px; letter-spacing: 1px;"><?= htmlspecialchars($car['license_plate']) ?></td>
                            <td>
                                <?php 
                                    $statusClass = 'badge-available';
                                    if($car['status'] == 'Rented') $statusClass = 'badge-rented';
                                    if($car['status'] == 'Maintenance') $statusClass = 'badge-maintenance';
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= $car['status'] ?></span>
                            </td>
                            <td>
                                <a href="edit_car.php?id=<?= $car['car_id'] ?>" class="btn btn-edit">Edit</a>
                                <a href="delete_car.php?id=<?= $car['car_id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to remove this vehicle from inventory?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="no-data">No individual fleet vehicles registered yet. Link one to a category above!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
