<?php
session_start();

if (!isset($_SESSION['MaNguoiDung'])) {
    header('Location: login.php');
    exit();
}

include '../database/db.php';

$stmt = $conn->prepare("SELECT * FROM ChuongHoc ORDER BY ThuTu ASC");
$stmt->execute();
$chuongHoc = $stmt->fetchAll(PDO::FETCH_ASSOC);

$chuongBaiHoc = [];
foreach ($chuongHoc as $chuong) {
    $stmt = $conn->prepare("SELECT * FROM BaiHoc WHERE MaChuong = :maChuong ORDER BY ThuTu ASC");
    $stmt->bindParam(':maChuong', $chuong['MaChuong'], PDO::PARAM_INT);
    $stmt->execute();
    $baiHoc = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($baiHoc as $bai) {
        $stmt = $conn->prepare("SELECT * FROM VideoBaiHoc WHERE MaBaiHoc = :maBaiHoc ORDER BY MaVideo ASC");
        $stmt->bindParam(':maBaiHoc', $bai['MaBaiHoc'], PDO::PARAM_INT);
        $stmt->execute();
        $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Video Bài Giảng</title>

    <?php include '../includes/styles.php'; ?>
    
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <!-- Main Start -->
    <div class="container-fluid pt-5">
        <div class="container pb-5">
            <h1 class="text-center mb-4">Danh sách video bài giảng</h1>
            
            <!-- Hiển thị danh sách chương học và bài học -->
            <?php if (!empty($chuongBaiHoc)): ?>
                <?php foreach ($chuongBaiHoc as $tenChuong => $baiHocs): ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5><?= htmlspecialchars($tenChuong); ?></h5>
                        </div>
                        <ul class="list-group">
                            <?php foreach ($baiHocs as $baiHoc): ?>
                                <?php foreach ($baiHoc['Videos'] as $video): ?>
                                    <li class="list-group-item">
                                        <a href="<?= htmlspecialchars($video['DuongDanVideo']); ?>" target="_blank" class="text-primary">
                                            <strong><?= htmlspecialchars($baiHoc['TenBai']); ?></strong>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Hiện tại chưa có video bài giảng nào.</p>
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
