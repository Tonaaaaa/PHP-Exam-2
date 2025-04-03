<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h2 class="mb-0">Đăng Ký Thành Công</h2>
    </div>
    <div class="card-body text-center">
        <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
        </div>
        <h3>Đăng ký học phần của bạn đã được hoàn tất!</h3>
        <p class="lead">Bạn có thể xem các học phần đã đăng ký trong mục Học Phần Đã Đăng Ký.</p>
        <div class="mt-4">
            <a href="index.php?controller=registration&action=index" class="btn btn-primary me-2">Xem Học Phần Đã Đăng Ký</a>
            <a href="index.php?controller=course&action=index" class="btn btn-secondary">Đăng Ký Thêm Học Phần</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>