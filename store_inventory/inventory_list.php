<?php
$conn = new mysqli("localhost", "root", "Sephia0312$$", "store_inventory");
$conn->set_charset("utf8mb4");

$result = $conn->query("
  SELECT 
    i.product_id,
    p.name_zh,
    p.name_en,
    i.current_stock,
    i.last_updated
  FROM 
    inventory i
  JOIN 
    products p ON i.product_id = p.product_id
  ORDER BY p.name_zh ASC
");
?>

<h2>📊 庫存查詢 / Inventory Lookup</h2>
<table border="1" cellpadding="6" cellspacing="0">
  <tr style="background-color:#f0f0f0;">
    <th>商品編號 / Product ID</th>
    <th>商品名稱 / Product Name</th>
    <th>庫存數量 / Stock</th>
    <th>最後更新 / Last Updated</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?= $row['product_id'] ?></td>
      <td><?= $row['name_zh'] ?> / <?= $row['name_en'] ?></td>
      <td><?= $row['current_stock'] ?></td>
      <td><?= $row['last_updated'] ?></td>
    </tr>
  <?php } ?>
</table>