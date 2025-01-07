<?php
session_start();
require_once 'Database.php';
require_once 'navbar.php';
require_once 'check_role.php';


checkRole('admin');
$itemsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); 

$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) && strtolower($_GET['order']) === 'desc' ? 'DESC' : 'ASC';

$validColumns = ['id', 'name', 'price', 'quantity_available'];
if (!in_array($sortColumn, $validColumns)) {
    $sortColumn = 'id';
}

$offset = ($page - 1) * $itemsPerPage;

$db = Database::getInstance()->getConnection();


$countQuery = "SELECT COUNT(*) AS total FROM products";
$countStmt = $db->prepare($countQuery);
$countStmt->execute();
$totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

$query = "SELECT * FROM products ORDER BY $sortColumn $sortOrder LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($query);
$stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPages = ceil($totalRows / $itemsPerPage);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: blue;
        }

        .pagination {
            margin: 20px 0;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            color: blue;
        }

        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }

        .btn-warning {
            background-color: orange;
            color: white;
            padding: 5px;
        }

        .btn-danger {
            background-color: red;
            color: white;
            border: none;
            padding: 5px;
        }

        .btn-primary {
            background-color: blue;
            color: white;
            padding: 5px;
        }
    </style>
</head>

<body>
    <h1>Products List</h1>
    <a href="product/create" class="text-end">Add New Product</a>
    <table>
        <thead>
            <tr>
                <th><a href="?sort=id&order=<?php echo $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">ID</a></th>
                <th><a href="?sort=name&order=<?php echo $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Name</a></th>
                <th><a href="?sort=price&order=<?php echo $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Price</a></th>
                <th><a href="?sort=quantity_available&order=<?php echo $sortOrder === 'ASC' ? 'desc' : 'asc'; ?>">Quantity</a></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity_available']); ?></td>
                        <td>
                            <a class="btn btn-warning" href="product/edit?id=<?php echo $product['id']; ?>">Edit</a>
                            <form action="/product/delete" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                            </form>

                            <a class="btn btn-primary" href="/product/purchase?id=<?php echo $product['id']; ?>">Purchase</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo strtolower($sortOrder); ?>" class="<?php echo $i === $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
</body>

</html>