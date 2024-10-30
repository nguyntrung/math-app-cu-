<?php
session_start();

if (!isset($_SESSION['MaNguoiDung'])) {
    header('Location: login.php');
    exit();
}

include '../database/db.php';

// Kiểm tra nếu có mã bài học trong URL
if (!isset($_GET['maBaiHoc'])) {
    echo "Bài học không tồn tại!";
    exit();
}

$maBaiHoc = $_GET['maBaiHoc'];

// Lấy thông tin video và tên bài học
$stmt = $conn->prepare("SELECT * FROM VideoBaiHoc WHERE MaBaiHoc = :maBaiHoc");
$stmt->bindParam(':maBaiHoc', $maBaiHoc, PDO::PARAM_INT);
$stmt->execute();
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kiểm tra nếu có video
if (empty($videos)) {
    echo "Không tìm thấy video cho bài học này!";
    exit();
}

// Lấy tên bài học
$stmtTitle = $conn->prepare("SELECT TenBai FROM BaiHoc WHERE MaBaiHoc = :maBaiHoc");
$stmtTitle->bindParam(':maBaiHoc', $maBaiHoc, PDO::PARAM_INT);
$stmtTitle->execute();
$lesson = $stmtTitle->fetch(PDO::FETCH_ASSOC);
$tenBaiHoc = $lesson['TenBai'];

// Lấy bài học tiếp theo
$stmtNext = $conn->prepare("SELECT MaBaiHoc, TenBai FROM BaiHoc WHERE MaBaiHoc > :maBaiHoc ORDER BY MaBaiHoc ASC LIMIT 1");
$stmtNext->bindParam(':maBaiHoc', $maBaiHoc, PDO::PARAM_INT);
$stmtNext->execute();
$nextLesson = $stmtNext->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chi tiết bài giảng</title>

    <?php include '../includes/styles.php'; ?>
    
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <!-- Main Start -->
    <div class="container-fluid pt-5">
        <div class="container pb-5">
            <h4><?= htmlspecialchars($tenBaiHoc); ?></h4> <!-- Hiển thị tên bài học -->
            <?php foreach ($videos as $video): ?>
                <video controls class="w-100">
                    <source src="<?= htmlspecialchars($video['DuongDanVideo']); ?>" type="video/mp4">
                    Trình duyệt của bạn không hỗ trợ video.
                </video>
                <div>
                    <strong>Bài học liên quan:</strong>
                </div>
                <ul class="list-unstyled">
                    <li>- <a href="theory_lessons.php?maBaiHoc=<?= $maBaiHoc; ?>">Lý thuyết</a></li>
                    <li>- <a href="essay_detail.php?maBaiHoc=<?= $maBaiHoc; ?>">Bài tập tự luận</a></li>
                    <li>- <a href="quiz_detail.php?maBaiHoc=<?= $maBaiHoc; ?>">Bài tập trắc nghiệm</a></li>
                </ul>
            <?php endforeach; ?>

            <?php if ($nextLesson): ?>
                <strong>Bài học tiếp theo:</strong></br>
                <a href="video_lessons_detail.php?maBaiHoc=<?= $nextLesson['MaBaiHoc']; ?>">
                    <?= htmlspecialchars($nextLesson['TenBai']); ?>
                </a>
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
