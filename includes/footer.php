<?php
include '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['ten'];
    $noi_dung = $_POST['noidung'];

    $stmt = $conn->prepare("INSERT INTO PhanHoi (Ten, NoiDung) VALUES (:ten, :noidung)");
    $stmt->bindParam(':ten', $ten);
    $stmt->bindParam(':noidung', $noi_dung);
    
    if ($stmt->execute()) {
        $success_message = 'Gửi phản hồi thành công!';
    } else {
        $success_message = 'Đã xảy ra lỗi khi gửi phản hồi.';
    }
}
?>

<!-- Footer Start -->
<div class="container-fluid bg-secondary text-white py-5 px-sm-3 px-md-5">
    <div class="row pt-5">
        <div class="col-lg-3 col-md-6 mb-5">
            <a href="" class="navbar-brand font-weight-bold text-primary m-0 mb-4 p-0" style="font-size: 40px; line-height: 40px;">
                <i class="flaticon-043-teddy-bear"></i>
                <span class="text-white">ToanLop5.vn</span>
            </a>
            <p>Labore dolor amet ipsum ea, erat sit ipsum duo eos. Volup amet ea dolor et magna dolor, elitr rebum duo est sed diam elitr. Stet elitr stet diam duo eos rebum ipsum diam ipsum elitr.</p>
            <div class="d-flex justify-content-start mt-4">
                <a class="btn btn-outline-primary rounded-circle text-center mr-2 px-0"
                    style="width: 38px; height: 38px;" href="#"><i class="fab fa-twitter"></i></a>
                <a class="btn btn-outline-primary rounded-circle text-center mr-2 px-0"
                    style="width: 38px; height: 38px;" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-outline-primary rounded-circle text-center mr-2 px-0"
                    style="width: 38px; height: 38px;" href="#"><i class="fab fa-linkedin-in"></i></a>
                <a class="btn btn-outline-primary rounded-circle text-center mr-2 px-0"
                    style="width: 38px; height: 38px;" href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5">
            <h3 class="text-primary mb-4">Liên hệ</h3>
            <div class="d-flex">
                <h4 class="fa fa-map-marker-alt text-primary"></h4>
                <div class="pl-3">
                    <h5 class="text-white">Đia chỉ</h5>
                    <p>123 Street, New York, USA</p>
                </div>
            </div>
            <div class="d-flex">
                <h4 class="fa fa-envelope text-primary"></h4>
                <div class="pl-3">
                    <h5 class="text-white">Email</h5>
                    <p>info@example.com</p>
                </div>
            </div>
            <div class="d-flex">
                <h4 class="fa fa-phone-alt text-primary"></h4>
                <div class="pl-3">
                    <h5 class="text-white">Điện thoại</h5>
                    <p>+012 345 67890</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5">
            <h3 class="text-primary mb-4">Liên kết nhanh</h3>
            <div class="d-flex flex-column justify-content-start">
                <a class="text-white mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Trang chủ</a>
                <a class="text-white mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Giới thiệu về chúng tôi</a>
                <a class="text-white mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Khóa học</a>
                <a class="text-white" href="#"><i class="fa fa-angle-right mr-2"></i>Liên hệ với chúng tôi</a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-5">
            <h3 class="text-primary mb-4">Phản hồi</h3>
            <form action="../public/" method="POST">
                <div class="form-group">
                    <input type="text" class="form-control border-0 py-4" name="ten" placeholder="Nhập tên" required="required" />
                </div>
                <div class="form-group">
                    <textarea class="form-control border-0 py-4" name="noidung" placeholder="Nội dung" required="required"></textarea>
                </div>
                <div>
                    <button class="btn btn-primary btn-block border-0 py-3" type="submit">Gửi</button>
                </div>
            </form>
        </div>
    </div>
    <div class="container-fluid pt-5" style="border-top: 1px solid rgba(23, 162, 184, .2);;">
        <p class="m-0 text-center text-white">
            &copy; <a class="text-primary font-weight-bold" href="#">Your Site Name</a>. All Rights Reserved. 
            
            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
            Designed by <a class="text-primary font-weight-bold" href="https://htmlcodex.com">HTML Codex</a>
        </p>
    </div>
</div>
<!-- Footer End -->