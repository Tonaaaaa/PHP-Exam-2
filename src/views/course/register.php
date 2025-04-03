<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Đăng Ký Học Phần</h2>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h4>Bạn đang đăng ký học phần sau:</h4>
            <p><strong>Tên Học Phần:</strong> <?php echo $course['TenHP']; ?></p>
            <p><strong>Mã Học Phần:</strong> <?php echo $course['MaHP']; ?></p>
            <p><strong>Số Tín Chỉ:</strong> <?php echo $course['SoTinChi']; ?></p>
        </div>

        <form action="index.php?controller=registration&action=store" method="POST">
            <input type="hidden" name="maHP" value="<?php echo $course['MaHP']; ?>">

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?controller=course&action=index" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-success">Xác Nhận Đăng Ký</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>