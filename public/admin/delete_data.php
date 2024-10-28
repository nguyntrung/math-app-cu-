<?php
include '../../database/db.php';

if (!isset($_GET['table'])) {
    echo "Không có bảng nào được chọn.";
    exit;
}

$table = $_GET['table'];

// Xử lý khi người dùng nhấn nút xóa
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    try {
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        if ($stmt->execute([$deleteId])) {
            echo "Dữ liệu đã được xóa thành công!";
        } else {
            echo "Lỗi khi xóa dữ liệu!";
        }
    } catch (PDOException $e) {
        echo "Lỗi khi xóa dữ liệu: " . $e->getMessage();
    }
}

// Lấy dữ liệu từ bảng
try {
    $stmt = $conn->query("SELECT * FROM $table");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columns = array_keys($data[0]);
} catch (PDOException $e) {
    echo "Lỗi khi truy xuất dữ liệu: " . $e->getMessage();
    exit;
}
?>

<!-- Hiển thị dữ liệu dưới dạng bảng với nút xóa -->
<h2>Dữ liệu trong bảng <?php echo htmlspecialchars($table); ?></h2>
<table border="1">
    <tr>
        <?php foreach ($columns as $column): ?>
            <th><?php echo htmlspecialchars($column); ?></th>
        <?php endforeach; ?>
        <th>Thao Tác</th>
    </tr>
    <?php foreach ($data as $row): ?>
        <tr>
            <?php foreach ($row as $cell): ?>
                <td><?php echo htmlspecialchars($cell); ?></td>
            <?php endforeach; ?>
            <td>
                <a href="delete_data.php?table=<?php echo htmlspecialchars($table); ?>&delete_id=<?php echo htmlspecialchars($row['id']); ?>"
                   onclick="return confirm('Bạn có chắc chắn muốn xóa dữ liệu này?');">
                   Xóa
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
