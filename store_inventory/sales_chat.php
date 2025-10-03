<?php
$conn = new mysqli("localhost", "root", "Sephia0312$$", "store_inventory");
$conn->set_charset("utf8mb4");

$response = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = (int)$_POST["product_id"];
  $quantity = (int)$_POST["quantity"];
  $staff = $_POST["staff"];

  // 查詢商品名稱與庫存
  $sql = "SELECT p.name_zh, p.name_en, i.current_stock FROM products p JOIN inventory i ON p.product_id = i.product_id WHERE p.product_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    $name_zh = $row["name_zh"];
    $name_en = $row["name_en"];
    $stock = $row["current_stock"];

    if ($quantity <= $stock) {
      // 扣減庫存
      $update_sql = "UPDATE inventory SET current_stock = current_stock - ?, last_updated = NOW() WHERE product_id = ?";
      $stmt = $conn->prepare($update_sql);
      $stmt->bind_param("ii", $quantity, $product_id);
      $stmt->execute();

      // 寫入操作紀錄
      $log_sql = "INSERT INTO activity_log (product_id, action_type, quantity, staff, notes) VALUES (?, 'chat_sale', ?, ?, 'Chat demo')";
      $stmt = $conn->prepare($log_sql);
      $stmt->bind_param("iiss", $product_id, $quantity, $staff, $notes = "Chat demo");
      $stmt->execute();

      $response = "✅ 已成功銷售 $quantity 件「$name_zh / $name_en」，剩餘庫存：".($stock - $quantity);
    } else {
      $response = "⚠️ 庫存不足！「$name_zh / $name_en」目前只有 $stock 件";
    }
  } else {
    $response = "❌ 找不到商品編號 $product_id";
  }
}
?>

<h2>💬 銷售對話模擬 / Sales Chat Demo</h2>
<form method="POST">
  <label>商品編號 / Product ID:</label><br>
  <input type="number" name="product_id" required><br><br>

  <label>銷售數量 / Quantity:</label><br>
  <input type="number" name="quantity" required><br><br>

  <label>操作人員 / Staff:</label><br>
  <input type="text" name="staff" value="Nathan"><br><br>

  <button type="submit">🛒 銷售 / Sell</button>
</form>

<?php if ($response) { echo "<p><strong>系統回覆 / System Response:</strong><br>$response</p>"; } ?>