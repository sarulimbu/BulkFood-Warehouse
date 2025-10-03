<?php
$conn = new mysqli("localhost", "root", "Sephia0312$$", "store_inventory");
$conn->set_charset("utf8mb4");

// 📥 批量匯入 CSV
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["sales_file"])) {
  $file = $_FILES["sales_file"]["tmp_name"];
  $handle = fopen($file, "r");
  fgetcsv($handle); // 跳過標題列

  $success_count = 0;
  $error_count = 0;

  while (($data = fgetcsv($handle)) !== FALSE) {
    $product_id = (int)$data[0];
    $quantity_sold = (int)$data[1];
    $staff = $data[2];
    $notes = $data[3];

    $check_sql = "SELECT current_stock FROM inventory WHERE product_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $update_sql = "UPDATE inventory SET current_stock = current_stock - ?, last_updated = NOW() WHERE product_id = ?";
      $stmt = $conn->prepare($update_sql);
      $stmt->bind_param("ii", $quantity_sold, $product_id);
      $stmt->execute();

      $log_sql = "INSERT INTO activity_log (product_id, action_type, quantity, staff, notes) VALUES (?, 'import_sales', ?, ?, ?)";
      $stmt = $conn->prepare($log_sql);
      $stmt->bind_param("iiss", $product_id, $quantity_sold, $staff, $notes);
      $stmt->execute();

      $success_count++;
    } else {
      $error_count++;
    }
  }

  fclose($handle);
  echo "<p style='color:green;'>✅ 成功匯入 $success_count 筆銷售紀錄 / $success_count sales records imported successfully</p>";
  if ($error_count > 0) {
    echo "<p style='color:red;'>⚠️ 有 $error_count 筆商品編號不存在 / $error_count product IDs not found</p>";
  }
}

// ✍️ 手動匯入單筆銷售
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["manual_submit"])) {
  $product_id = (int)$_POST["product_id"];
  $quantity_sold = (int)$_POST["quantity"];
  $staff = $_POST["staff"];
  $notes = $_POST["notes"];

  $check_sql = "SELECT current_stock FROM inventory WHERE product_id = ?";
  $stmt = $conn->prepare($check_sql);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $update_sql = "UPDATE inventory SET current_stock = current_stock - ?, last_updated = NOW() WHERE product_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $quantity_sold, $product_id);
    $stmt->execute();

    $log_sql = "INSERT INTO activity_log (product_id, action_type, quantity, staff, notes) VALUES (?, 'import_sales', ?, ?, ?)";
    $stmt = $conn->prepare($log_sql);
    $stmt->bind_param("iiss", $product_id, $quantity_sold, $staff, $notes);
    $stmt->execute();

    echo "<p style='color:green;'>✅ 單筆銷售已匯入 / Single sale imported successfully</p>";
  } else {
    echo "<p style='color:red;'>❌ 商品編號不存在 / Product ID not found</p>";
  }
}
?>

<h2>📥 銷售匯入 / Import Sales</h2>

<!-- 📤 批量匯入 CSV -->
<h3>📤 批量匯入 / Bulk Import via CSV</h3>
<form method="POST" enctype="multipart/form-data">
  <label>上傳銷售紀錄 / Upload Sales CSV:</label><br>
  <input type="file" name="sales_file" accept=".csv" required><br><br>
  <button type="submit">匯入 / Import CSV</button>
</form>

<p>📄 CSV 格式：</p>
<pre>
product_id,quantity,staff,notes
1,20,Nathan,店面銷售
2,15,Alice,網店出貨
</pre>

<hr>

<!-- ✍️ 手動匯入表單 -->
<h3>✍️ 手動匯入 / Manual Entry</h3>
<form method="POST">
  <input type="hidden" name="manual_submit" value="1">
  <label>商品編號 / Product ID:</label><br>
  <input type="number" name="product_id" required><br><br>

  <label>銷售數量 / Quantity Sold:</label><br>
  <input type="number" name="quantity" required><br><br>

  <label>操作人員 / Staff:</label><br>
  <input type="text" name="staff"><br><br>

  <label>備註 / Notes:</label><br>
  <textarea name="notes"></textarea><br><br>

  <button type="submit">匯入 / Import Manually</button>
</form>