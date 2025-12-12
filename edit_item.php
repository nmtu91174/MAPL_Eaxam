<?php
require_once './db_connect.php';

$id = $item_code = $item_name = $quantity = $expried_date = $note = "";
$code_err = $name_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    if (empty(trim($_POST["item_code"]))) {
        $code_err = "Item Code is required.";
    } else {
        $item_code = trim($_POST["item_code"]);
    }

    if (empty(trim($_POST["item_name"]))) {
        $name_err = "Item Name is required.";
    } else {
        $item_name = trim($_POST["item_name"]);
    }

    if (!preg_match('/^[a-zA-Z0-9\s]*$/', $item_code)) {
        $code_err = "Item Code must not contain special characters.";
    }
    if (!preg_match('/^[a-zA-Z0-9\s]*$/', $item_name)) {
        $name_err = "Item Name must not contain special characters.";
    }

    $quantity = $_POST["quantity"] ?? 0;
    $expried_date = $_POST["expried_date"] ?? date('Y-m-d');
    $note = trim($_POST["note"]) ?? NULL;

    if (empty($code_err) && empty($name_err)) {
        $sql = "UPDATE item_sale SET item_code=?, item_name=?, quantity=?, expried_date=?, note=? WHERE id=?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssdssi", $item_code, $item_name, $quantity, $expried_date, $note, $id);

            if ($stmt->execute()) {
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Error updating record.";
            }
            $stmt->close();
        }
    }
} else if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    $sql = "SELECT item_code, item_name, quantity, expried_date, note FROM item_sale WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $item_code = $row["item_code"];
                $item_name = $row["item_name"];
                $quantity = $row["quantity"];
                $expried_date = $row["expried_date"];
                $note = $row["note"];
            } else {
                header("location: index.php");
                exit();
            }
        }
        $stmt->close();
    }
} else {
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
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
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-4">✏️ Edit Item</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label class="form-label">Item Code <span class="text-danger">(*)</span></label>
                        <input type="text" name="item_code" class="form-control <?php echo (!empty($code_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($item_code); ?>">
                        <div class="invalid-feedback"><?php echo $code_err; ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item Name <span class="text-danger">(*)</span></label>
                        <input type="text" name="item_name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($item_name); ?>">
                        <div class="invalid-feedback"><?php echo $name_err; ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" step="any" class="form-control" value="<?php echo htmlspecialchars($quantity); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expired Date</label>
                        <input type="date" name="expried_date" class="form-control" value="<?php echo htmlspecialchars($expried_date); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea name="note" class="form-control"><?php echo htmlspecialchars($note ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Item</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <footer class="footer mt-auto py-3 bg-light fixed-bottom">
        <div class="container text-center">
            <span class="text-muted">© <?php echo date("Y"); ?> V_Store - FPT Aptech MAPL</span>
        </div>
    </footer>
</body>

</html>
<?php
$conn->close();
?>