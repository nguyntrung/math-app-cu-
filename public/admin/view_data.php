<?php
session_start();

if (!isset($_SESSION['MaNguoiDung'])) {
    header('Location: login.php');
    exit();
}

include '../../database/db.php';
?>



<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản Lý Dữ Liệu</title>
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>

<style>
    .collapse-inner {
    max-height: 300px; /* Chiều cao tối đa cho phần chứa */
    overflow-y: auto;  /* Thêm cuộn dọc nếu nội dung vượt quá chiều cao */
}

</style>

<body id="page-top">

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Quản lý Dữ Liệu</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">Quản Lý Dữ Liệu</div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Dữ Liệu</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Danh sách các dữ liệu</h6>
                        <?php
                            // Hàm chuyển đổi tên bảng từ không dấu sang có dấu
                            function convertTableName($tableName) {
                                // Thay thế dấu gạch dưới bằng khoảng trắng
                                // Cú pháp nếu bảng có dấu gạch dưới, nhưng theo yêu cầu không có gạch dưới
                                // Viết hoa chữ cái đầu tiên của mỗi từ
                                switch ($tableName) {
                                    case 'baigiai':
                                        return 'Bài Giải';
                                    case 'baihoc':
                                        return 'Bài Học';
                                    case 'cauhoitracnghiem':
                                        return 'Câu Hỏi Trắc Nghiệm';
                                    case 'cauhoituluan':
                                        return 'Câu Hỏi Tự Luận';
                                    case 'cautraloi':
                                        return 'Câu Trả Lời';
                                    case 'chuonghoc':
                                        return 'Chương Học';
                                    case 'lythuyet':
                                        return 'Lý Thuyết';
                                    case 'dangkythanhvien':
                                        return 'Đăng Ký Thành Viên';
                                    case 'nguoidung':
                                        return 'Người Dùng';
                                    case 'thanhtoan':
                                        return 'Thanh Toán';
                                    case 'tiendohoctap':
                                        return 'Tiến Độ Học Tập';
                                    case 'videobaihoc':
                                        return 'Video Bài Học';
                                    
                                    default:
                                        // Nếu không có tên nào khớp, trả về tên gốc với viết hoa chữ cái đầu
                                        return ucwords(str_replace('', ' ', $tableName));
                                }
                            }

                            try {
                                $stmt = $conn->query("SHOW TABLES");
                                $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                            } catch (PDOException $e) {
                                echo "Lỗi khi lấy danh sách bảng: " . $e->getMessage();
                            }

                            // Danh sách các bảng không hiển thị
                            $excludeTables = ['dangkythanhvien', 'nguoidung', 'tiendohoctap', 'thanhtoan', 'cautraloi'];

                            // Hiển thị tên các bảng trong dropdown
                            if ($tables): ?>
                                <?php foreach ($tables as $table): ?>
                                    <?php
                                        // Kiểm tra xem bảng có nằm trong danh sách loại bỏ không
                                        if (!in_array($table, $excludeTables)): ?>
                                            <a class="collapse-item" href="view_data.php?table=<?php echo htmlspecialchars($table); ?>">
                                                <?php echo htmlspecialchars(convertTableName($table)); ?>
                                            </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="collapse-item">Không có bảng nào trong cơ sở dữ liệu.</p>
                            <?php endif; ?>
                    </div>
                </div>
            </li>


            <?php
            // Lấy tên bảng từ URL (nếu có)
            $selectedTable = isset($_GET['table']) ? $_GET['table'] : null;
            ?>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Thao Tác</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <!-- Nút thao tác cho bảng đã chọn -->
                    <div class="bg-white py-2 collapse-inner rounded" style="max-height: 300px; overflow-y: auto;">
                        <?php if ($selectedTable): ?>
                            <h6 class="collapse-header">Bảng <?php echo htmlspecialchars(convertTableName($selectedTable)); ?></h6>
                            <a class="collapse-item" href="add_data.php?table=<?php echo htmlspecialchars($selectedTable); ?>">Thêm Dữ Liệu</a>
                            <a class="collapse-item" href="edit_data.php?table=<?php echo htmlspecialchars($selectedTable); ?>">Sửa Dữ Liệu</a>
                            <a class="collapse-item" href="delete_data.php?table=<?php echo htmlspecialchars($selectedTable); ?>">Xóa Dữ Liệu</a>
                        <?php else: ?>
                            <p class="collapse-item">Vui lòng chọn một bảng để thao tác.</p>
                        <?php endif; ?>
                    </div>

                </div>
            </li>

            <!-- Nav Item - Đăng Xuất -->
            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#logoutModal" href = "admin.php">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Thoát</span>
                </a>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Người dùng</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Dữ Liệu Bảng</h1>

                    <?php
                    // Kết nối đến cơ sở dữ liệu
                    $dsn = 'mysql:host=localhost;dbname=quanlyhoctoan';
                    $username = 'root';
                    $password = '';

                    try {
                        $db = new PDO($dsn, $username, $password);
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Lấy tên bảng từ URL
                        $selectedTable = isset($_GET['table']) ? $_GET['table'] : null;

                        if ($selectedTable) {
                            // Truy vấn dữ liệu từ bảng đã chọn
                            $stmt = $db->prepare("SELECT * FROM " . htmlspecialchars($selectedTable));
                            $stmt->execute();
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Kiểm tra có dữ liệu không
                            if ($data): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <?php foreach ($data[0] as $column => $value): ?>
                                                <th><?php echo htmlspecialchars(ucfirst($column)); ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data as $row): ?>
                                            <tr>
                                                <?php foreach ($row as $value): ?>
                                                    <td><?php echo htmlspecialchars($value); ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>Không có dữ liệu trong bảng này.</p>
                            <?php endif;
                        } else {
                            echo "<p>Vui lòng chọn một bảng để hiển thị dữ liệu.</p>";
                        }
                    } catch (PDOException $e) {
                        echo "Lỗi: " . $e->getMessage();
                    }
                    ?>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>© 2024 Công ty TNHH ABC</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>