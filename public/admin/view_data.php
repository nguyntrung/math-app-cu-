<?php
session_start();

if (!isset($_SESSION['MaNguoiDung'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['HoTen'];
include '../../database/db.php';

// Xử lý thêm dữ liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['Ma'])) {
    try {
        $columns = array_keys($_POST);
        $values = array_values($_POST);
        $placeholders = array_fill(0, count($values), '?');

        $sql = "INSERT INTO " . htmlspecialchars($selectedTable) . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $db->prepare($sql);
        $stmt->execute($values);
        echo "<script>alert('Thêm dữ liệu thành công!');</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Lỗi khi thêm dữ liệu: " . $e->getMessage() . "');</script>";
    }
}

// Xử lý sửa dữ liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    try {
        $ma = $_POST['Ma'];
        $setPart = '';
        foreach ($_POST as $key => $value) {
            if ($key !== 'edit' && $key !== 'Ma') {
                $setPart .= "$key = :$key, ";
            }
        }
        $setPart = rtrim($setPart, ', ');

        $sql = "UPDATE " . htmlspecialchars($selectedTable) . " SET $setPart WHERE Ma = :ma";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':ma', $ma);

        foreach ($_POST as $key => $value) {
            if ($key !== 'edit' && $key !== 'Ma') {
                $stmt->bindParam(":$key", $value);
            }
        }
        $stmt->execute();
        echo "<script>alert('Sửa dữ liệu thành công!');</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Lỗi khi sửa dữ liệu: " . $e->getMessage() . "');</script>";
    }
}

// Xử lý xóa dữ liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    try {
        $ma = $_POST['deleteMa'];
        $sql = "DELETE FROM " . htmlspecialchars($selectedTable) . " WHERE Ma = :ma";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':ma', $ma);
        $stmt->execute();
        echo "<script>alert('Xóa dữ liệu thành công!');</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Lỗi khi xóa dữ liệu: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản Lý Dữ Liệu</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>

<style>
    .collapse-inner {
        max-height: 300px;
        overflow-y: auto;
    }
</style>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Quản lý Dữ Liệu</div>
            </a>
            <hr class="sidebar-divider">
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
                        function convertTableName($tableName) {
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
                                    return ucwords(str_replace('', ' ', $tableName));
                            }
                        }

                        try {
                            $stmt = $conn->query("SHOW TABLES");
                            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        } catch (PDOException $e) {
                            echo "Lỗi khi lấy danh sách bảng: " . $e->getMessage();
                        }

                        $excludeTables = ['dangkythanhvien', 'nguoidung', 'tiendohoctap', 'thanhtoan', 'cautraloi'];

                        if ($tables): ?>
                            <?php foreach ($tables as $table): ?>
                                <?php
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
            $selectedTable = isset($_GET['table']) ? $_GET['table'] : null;
            ?>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Thao Tác</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded" style="max-height: 300px; overflow-y: auto;">
                        <?php if ($selectedTable): ?>
                            <h6 class="collapse-header">Bảng <?php echo htmlspecialchars(convertTableName($selectedTable)); ?></h6>
                            <a class="collapse-item" href="#addData" data-toggle="modal">Thêm Dữ Liệu</a>
                            <a class="collapse-item" href="#editData" data-toggle="modal">Sửa Dữ Liệu</a>
                            <a class="collapse-item" href="#deleteData" data-toggle="modal">Xóa Dữ Liệu</a>
                        <?php else: ?>
                            <p class="collapse-item">Vui lòng chọn một bảng để thao tác.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Thoát</span>
                </a>
            </li>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Dữ Liệu Bảng <?php echo htmlspecialchars(convertTableName($selectedTable)); ?> </h1>
                    <?php
                    $dsn = 'mysql:host=localhost;dbname=quanlyhoctoan';
                    $username = 'root';
                    $password = '';

                    try {
                        $db = new PDO($dsn, $username, $password);
                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $selectedTable = isset($_GET['table']) ? $_GET['table'] : null;

                        if ($selectedTable) {
                            $stmt = $db->prepare("SHOW COLUMNS FROM " . htmlspecialchars($selectedTable));
                            $stmt->execute();
                            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

                            $hasMaChuong = in_array('MaChuong', $columns);
                            $hasMaBaiHoc = in_array('MaBaiHoc', $columns);

                            $joinQuery = "SELECT " . htmlspecialchars($selectedTable) . ".*";
                            if ($hasMaChuong) {
                                $joinQuery .= ", ch.TenChuong";
                            }
                            if ($hasMaBaiHoc) {
                                $joinQuery .= ", bh.TenBai";
                            }
                            $joinQuery .= " FROM " . htmlspecialchars($selectedTable);
                            if ($hasMaChuong) {
                                $joinQuery .= " LEFT JOIN chuonghoc AS ch ON " . htmlspecialchars($selectedTable) . ".MaChuong = ch.MaChuong";
                            }
                            if ($hasMaBaiHoc) {
                                $joinQuery .= " LEFT JOIN baihoc AS bh ON " . htmlspecialchars($selectedTable) . ".MaBaiHoc = bh.MaBaiHoc";
                            }

                            try {
                                $stmt = $db->prepare($joinQuery);
                                $stmt->execute();
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                            } catch (PDOException $e) {
                                echo "Lỗi khi truy vấn dữ liệu: " . $e->getMessage();
                            }
                        }
                    } catch (PDOException $e) {
                        echo "Lỗi kết nối: " . $e->getMessage();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Thêm Dữ Liệu Modal -->
    <div class="modal fade" id="addData" tabindex="-1" role="dialog" aria-labelledby="addDataLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDataLabel">Thêm Dữ Liệu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="view_data.php?table=<?php echo htmlspecialchars($selectedTable); ?>" method="POST">
                    <div class="modal-body">
                        <?php
                        if ($selectedTable) {
                            $stmt = $db->prepare("SHOW COLUMNS FROM " . htmlspecialchars($selectedTable));
                            $stmt->execute();
                            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

                            foreach ($columns as $column): ?>
                                <div class="form-group">
                                    <label for="<?php echo htmlspecialchars($column); ?>"><?php echo htmlspecialchars(ucfirst($column)); ?></label>
                                    <input type="text" class="form-control" id="<?php echo htmlspecialchars($column); ?>" name="<?php echo htmlspecialchars($column); ?>" required>
                                </div>
                            <?php endforeach; 
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sửa Dữ Liệu Modal -->
    <div class="modal fade" id="editData" tabindex="-1" role="dialog" aria-labelledby="editDataLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataLabel">Sửa Dữ Liệu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="view_data.php?table=<?php echo htmlspecialchars($selectedTable); ?>" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="Ma" class="col-form-label">Mã (chọn bản ghi để sửa):</label>
                            <select class="form-control" id="Ma" name="Ma" required>
                                <option value="">Chọn Mã</option>
                                <?php
                                try {
                                    $stmt = $db->query("SELECT Ma FROM " . htmlspecialchars($selectedTable));
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo htmlspecialchars($row['Ma']); ?>"><?php echo htmlspecialchars($row['Ma']); ?></option>
                                    <?php endwhile; 
                                } catch (PDOException $e) {
                                    echo "Lỗi khi truy vấn dữ liệu: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                        if ($selectedTable) {
                            $stmt = $db->prepare("SHOW COLUMNS FROM " . htmlspecialchars($selectedTable));
                            $stmt->execute();
                            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

                            foreach ($columns as $column): ?>
                                <div class="form-group">
                                    <label for="edit_<?php echo htmlspecialchars($column); ?>"><?php echo htmlspecialchars(ucfirst($column)); ?></label>
                                    <input type="text" class="form-control" id="edit_<?php echo htmlspecialchars($column); ?>" name="edit_<?php echo htmlspecialchars($column); ?>" required>
                                </div>
                            <?php endforeach; 
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" name="edit" class="btn btn-primary">Sửa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Xóa Dữ Liệu Modal -->
    <div class="modal fade" id="deleteData" tabindex="-1" role="dialog" aria-labelledby="deleteDataLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDataLabel">Xóa Dữ Liệu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="view_data.php?table=<?php echo htmlspecialchars($selectedTable); ?>" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="deleteMa">Mã (chọn bản ghi để xóa):</label>
                            <select class="form-control" id="deleteMa" name="deleteMa" required>
                                <option value="">Chọn Mã</option>
                                <?php
                                try {
                                    $stmt = $db->query("SELECT Ma FROM " . htmlspecialchars($selectedTable));
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo htmlspecialchars($row['Ma']); ?>"><?php echo htmlspecialchars($row['Ma']); ?></option>
                                    <?php endwhile; 
                                } catch (PDOException $e) {
                                    echo "Lỗi khi truy vấn dữ liệu: " . $e->getMessage();
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" name="delete" class="btn btn-danger">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>


