<?php
$conn = new mysqli("localhost", "root", "Sephia0312$$", "store_inventory");
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];
    $staff = $_POST["staff"];
    $notes = $_POST["notes"];

    $update_sql = "UPDATE inventory SET current_stock = current_stock + ?, last_updated = NOW() WHERE product_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $quantity, $product_id);
    $stmt->execute();

    $insert_sql = "INSERT INTO stock_additions (product_id, quantity, staff, notes) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iiss", $product_id, $quantity, $staff, $notes);
    $stmt->execute();

    // Update current_stock in products table
    $update_sql = "UPDATE products SET current_stock = current_stock + ? WHERE product_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $quantity, $product_id);
    $stmt->execute();

    // Insert into activity_log
    $log_sql = "INSERT INTO activity_log (product_id, action_type, quantity, staff, notes) VALUES (?, 'add_stock', ?, ?, ?)";
    $stmt = $conn->prepare($log_sql);
    $stmt->bind_param("iiss", $product_id, $quantity, $staff, $notes);
    $stmt->execute();

    echo "<p>✅ Stock added successfully.</p>";
}
?>

<h2>Add Stock</h2>
<form method="POST">
    <label>Product:</label>
    <select name="product_id">
        <?php
        $result = $conn->query("SELECT product_id, name_zh, name_en FROM products WHERE is_active = TRUE");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['product_id']}'>{$row['name_zh']} / {$row['name_en']}</option>";
        }
        ?>
    </select><br><br>

    <label>Quantity:</label>
    <input type="number" name="quantity" required><br><br>

    <label>Staff:</label>
    <input type="text" name="staff" required><br><br>

    <label>Notes:</label>
    <textarea name="notes"></textarea><br><br>

    <button type="submit">Add Stock</button>
</form>