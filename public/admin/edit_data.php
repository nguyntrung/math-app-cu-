<?php
include '../../database/db.php';

if (!isset($_GET['table']) || !isset($_GET['id'])) {
    echo "Không có bảng hoặc ID nào được chọn.";
    exit;
}

$table = $_GET['table'];
$id = $_GET['id']; // Giả sử ID là khóa chính của bảng

// Lấy thông tin cột và dữ liệu hiện tại
try {
    $stmt = $conn->query("DESCRIBE $table");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Lỗi khi truy xuất dữ liệu: " . $e->getMessage();
    exit;
}

// Xử lý khi người dùng lưu thay đổi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [];
    foreach ($columns as $column) {
        $data[$column] = $_POST[$column] ?? null;
    }

    $updateFields = implode(", ", array_map(function($col) { return "$col = ?"; }, $columns));
    $stmt = $conn->prepare("UPDATE $table SET $updateFields WHERE id = ?");
    $params = array_values($data);
    $params[] = $id;

    if ($stmt->execute($params)) {
        echo "Dữ liệu đã được cập nhật thành công!";
    } else {
        echo "Lỗi khi cập nhật dữ liệu!";
    }
}
?>

<!-- Form để sửa dữ liệu -->
<h2>Sửa Dữ Liệu trong <?php echo htmlspecialchars($table); ?></h2>
<form method="POST">
    <?php foreach ($columns as $column): ?>
        <label for="<?php echo htmlspecialchars($column); ?>"><?php echo htmlspecialchars($column); ?></label>
        <input type="text" name="<?php echo htmlspecialchars($column); ?>" id="<?php echo htmlspecialchars($column); ?>" 
               value="<?php echo htmlspecialchars($record[$column]); ?>"><br>
    <?php endforeach; ?>
    <button type="submit">Lưu Thay Đổi</button>
</form>
