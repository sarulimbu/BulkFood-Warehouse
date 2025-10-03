<?php
$conn = new mysqli("localhost", "root", "Sephia0312$$", "store_inventory");
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$result = $conn->query("
  SELECT a.timestamp, p.name_zh, p.name_en, a.action_type, a.quantity, a.staff, a.notes
  FROM activity_log a
  JOIN products p ON a.product_id = p.product_id
  ORDER BY a.timestamp DESC
");
?>

<h2>📘 操作歷程記錄 / Activity Log</h2>
<table border="1" cellpadding="6" cellspacing="0">
  <tr style="background-color:#f0f0f0;">
    <th>時間 / Timestamp</th>
    <th>商品 / Product</th>
    <th>動作 / Action</th>
    <th>數量 / Quantity</th>
    <th>人員 / Staff</th>
    <th>備註 / Notes</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?= $row['timestamp'] ?></td>
      <td><?= $row['name_zh'] ?> / <?= $row['name_en'] ?></td>
      <td><?= $row['action_type'] ?></td>
      <td><?= $row['quantity'] ?></td>
      <td><?= $row['staff'] ?></td>
      <td><?= $row['notes'] ?></td>
    </tr>
  <?php } ?>
</table>