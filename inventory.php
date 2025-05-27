<?php
session_start();
include 'connection.php';

// ADD item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $stmt = $conn->prepare("INSERT INTO inventory (item_name, quantity, unit) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $_POST['item_name'], $_POST['quantity'], $_POST['unit']);
    $stmt->execute();
    header("Location: inventory.php");
    exit();
}

// EDIT item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_item'])) {
    $stmt = $conn->prepare("UPDATE inventory SET item_name = ?, quantity = ?, unit = ? WHERE id = ?");
    $stmt->bind_param("sisi", $_POST['edit_item_name'], $_POST['edit_quantity'], $_POST['edit_unit'], $_POST['edit_id']);
    $stmt->execute();
    header("Location: inventory.php");
    exit();
}

// DELETE item
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM inventory WHERE id = $id");
    header("Location: inventory.php");
    exit();
}

$result = $conn->query("SELECT * FROM inventory ORDER BY added_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color:rgb(44, 41, 41);
    }
    .table td, .table th {
      vertical-align: middle;
    }
    .btn {
      margin-right: 4px;
    }
    .card-header {
      font-weight: 600;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <h2 class="mb-4 text-center text-primary">📦 Inventory Management</h2>

  <!-- Add Item -->
  <div class="card shadow-sm mb-5">
    <div class="card-header bg-primary text-white">Add New Inventory Item</div>
    <div class="card-body">
      <form method="POST" class="row g-3">
        <input type="hidden" name="add_item" value="1">
        <div class="col-md-5">
          <input type="text" name="item_name" class="form-control" placeholder="Item Name" required>
        </div>
        <div class="col-md-3">
          <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
        </div>
        <div class="col-md-2">
          <input type="text" name="unit" class="form-control" placeholder="Unit (e.g. kg)">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-success w-100"> Add Item</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Inventory List -->
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">Inventory List</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Item</th>
              <th>Quantity</th>
              <th>Unit</th>
              <th>Added On</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php $index = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $index++ ?></td>
              <td><?= htmlspecialchars($row['item_name']) ?></td>
              <td><?= $row['quantity'] ?></td>
              <td><?= htmlspecialchars($row['unit']) ?></td>
              <td><?= $row['added_on'] ?></td>
              <td class="text-center">
                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>" title="Edit">
                  ✏
                </button>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this item?')" class="btn btn-sm btn-danger" title="Delete">
                  🗑
                </a>
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
              <div class="modal-dialog">
                <form method="POST" class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Inventory Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="edit_item" value="1">
                    <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                    <div class="mb-3">
                      <label class="form-label">Item Name</label>
                      <input type="text" name="edit_item_name" class="form-control" value="<?= htmlspecialchars($row['item_name']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Quantity</label>
                      <input type="number" name="edit_quantity" class="form-control" value="<?= $row['quantity'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Unit</label>
                      <input type="text" name="edit_unit" class="form-control" value="<?= htmlspecialchars($row['unit']) ?>">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          <?php endwhile; ?>
          <?php if ($index === 1): ?>
            <tr><td colspan="6" class="text-center text-muted">No inventory items found.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
