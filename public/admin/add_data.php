<?php
include '../../database/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table = $_POST['table'];
    $fields = $_POST['fields']; // Các trường nhập liệu sẽ được nhận từ form
    $values = $_POST['values']; // Các giá trị tương ứng

    // Câu truy vấn chuẩn bị
    $query = "INSERT INTO $table (" . implode(", ", $fields) . ") VALUES (" . implode(", ", array_fill(0, count($values), '?')) . ")";
    $stmt = $conn->prepare($query);

    if ($stmt->execute($values)) {
        echo "Dữ liệu đã được thêm thành công!";
    } else {
        echo "Có lỗi xảy ra khi thêm dữ liệu!";
    }
}
?>

<!-- Form để chọn bảng và thêm dữ liệu -->
<form action="add_data.php" method="POST">
    <label for="table">Chọn bảng:</label>
    <select name="table">
        <!-- Code PHP để hiển thị các tùy chọn bảng -->
    </select>
    <!-- Các trường và giá trị nhập liệu sẽ được thêm động dựa trên bảng -->
    <button type="submit">Thêm Dữ Liệu</button>
</form>
