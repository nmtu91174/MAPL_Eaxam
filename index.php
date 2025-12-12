<?php
require_once './db_connect.php';

$sql = "SELECT id, item_code, item_name, quantity, expried_date, note FROM item_sale ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>V_Store - Items List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <nav class="navbar navbar-light bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-warehouse me-2"></i> V_Store Inventory
            </a>
            <span class="navbar-text text-light">
                PHP Practical Exam
            </span>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Sale Items</h1>

        <a href="add_item.php" class="btn btn-danger mb-3">
            <i class="fas fa-plus me-1"></i> Add New
        </a>

        <?php
        if ($result->num_rows > 0) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-hover table-striped align-middle">';
            echo '<thead class="table-danger">';
            echo '<tr>';
            echo '<th>Id</th>';
            echo '<th>Code</th>';
            echo '<th>Name</th>';
            echo '<th>Quantity</th>';
            echo '<th>Expired Date</th>';
            echo '<th>Note</th>';
            echo '<th class="text-center">Actions</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["id"] . '</td>';
                echo '<td>' . htmlspecialchars($row["item_code"]) . '</td>';
                echo '<td>' . htmlspecialchars($row["item_name"]) . '</td>';
                echo '<td>' . $row["quantity"] . '</td>';
                echo '<td>' . date("d/m/Y", strtotime($row["expried_date"])) . '</td>';
                echo '<td>' . htmlspecialchars($row["note"]) . '</td>';

                echo '<td class="text-center">';
                echo '<a href="edit_item.php?id=' . $row["id"] . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                      </a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-info text-center">No items found in the database.</div>';
        }

        $conn->close();
        ?>
    </div>

    <footer class="footer mt-auto py-3 bg-light fixed-bottom">
        <div class="container text-center">
            <span class="text-muted">© <?php echo date("Y"); ?> V_Store - FPT Aptech PHPL-SET02</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>