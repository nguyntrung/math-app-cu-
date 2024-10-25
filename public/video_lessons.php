<?php
session_start();

if (!isset($_SESSION['MaNguoiDung'])) {
    header('Location: login.php');
    exit();
}

include '../database/db.php';

// Lấy danh sách các chương học
$stmt = $conn->prepare("SELECT * FROM ChuongHoc ORDER BY ThuTu ASC");
$stmt->execute();
$chuongHoc = $stmt->fetchAll();

// Lấy danh sách các bài học và video tương ứng
$chuongBaiHoc = [];
foreach ($chuongHoc as $chuong) {
    $stmt = $conn->prepare("SELECT * FROM BaiHoc WHERE MaChuong = :maChuong ORDER BY ThuTu ASC");
    $stmt->bindParam(':maChuong', $chuong['MaChuong']);
    $stmt->execute();
    $baiHoc = $stmt->fetchAll();

    foreach ($baiHoc as $bai) {
        $stmt = $conn->prepare("SELECT * FROM VideoBaiHoc WHERE MaBaiHoc = :maBaiHoc ORDER BY MaVideo ASC");
        $stmt->bindParam(':maBaiHoc', $bai['MaBaiHoc']);
        $stmt->execute();
        $videos = $stmt->fetchAll();

        $chuongBaiHoc[$chuong['TenChuong']][] = [
            'TenBai' => $bai['TenBai'],
            'Videos' => $videos
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bài giảng</title>

    <?php include '../includes/styles.php'; ?>
    
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <!-- Main Start -->
    <div class="container-fluid pt-5">
        <div class="container pb-5">
            <h1 class="text-center mb-4">Danh sách video bài giảng</h1>
            
            <?php foreach ($chuongBaiHoc as $tenChuong => $baiHocs): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h3><?php echo $tenChuong; ?></h3>
                    </div>
                    <div class="card-body">
                        <?php foreach ($baiHocs as $baiHoc): ?>
                            <div class="mb-4">
                                <ul class="list-group">
                                    <?php foreach ($baiHoc['Videos'] as $video): ?>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <strong><?php echo $video['TieuDe']; ?></strong>
                                                </div>
                                                <div class="col-md-4 text-right">
                                                    <a href="<?php echo $video['DuongDanVideo']; ?>" class="btn btn-primary" target="_blank">Xem video</a>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($chuongBaiHoc)): ?>
                <p>Hiện tại chưa có video bài giảng nào.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Main End -->

    <?php include '../includes/footer.php'; ?>

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary p-3 back-to-top"><i class="fa-solid fa-up-long"></i></a>

    <?php include '../includes/scripts.php'; ?>
    <script src="../assets/js/main.js"></script>
</body>
</html>