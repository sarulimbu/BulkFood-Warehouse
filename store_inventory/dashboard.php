<!DOCTYPE html>
<html lang="zh-Hant">
<head>
  <meta charset="UTF-8">
  <title>📦 庫存管理主頁 / Inventory Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; }
    h2 { color: #333; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
    th { background-color: #f0f0f0; }
    a.button {
      display: inline-block;
      padding: 8px 16px;
      background-color: #007BFF;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      transition: background-color 0.3s ease;
    }
    a.button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<h2>📦 庫存管理主頁 / Inventory Dashboard</h2>

<table>
  <tr>
    <th>功能 / Function</th>
    <th>操作 / Action</th>
  </tr>
  <tr>
    <td>➕ 新增庫存 / Add Stock</td>
    <td><a class="button" href="add_stock.php">前往 / Go</a></td>
  </tr>
  <tr>
    <td>📥 匯入銷售 / Import Sales</td>
    <td><a class="button" href="import_sales.php">前往 / Go</a></td>
  </tr>
  <tr>
    <td>📘 操作歷程 / Activity Log</td>
    <td><a class="button" href="activity_log.php">前往 / Go</a></td>
  </tr>
  <tr>
    <td>📊 庫存查詢 / Inventory Lookup</td>
    <td><a class="button" href="inventory_list.php">前往 / Go</a></td>
  </tr>
  <tr>
    <td>💬 銷售對話模擬 / Sales Chat Demo</td>
    <td><a class="button" href="sales_chat.php">前往 / Go</a></td>
  </tr>
</table>

</body>
</html>