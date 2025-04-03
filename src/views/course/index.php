<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Danh Sách Học Phần</h2>
    </div>
    <div class="card-body">
        <?php if (empty($courses)): ?>
            <div class="alert alert-info">Không có học phần nào khả dụng để đăng ký.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><?php echo $course['TenHP']; ?></h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Mã Học Phần:</strong> <?php echo $course['MaHP']; ?></p>
                                <p><strong>Số Tín Chỉ:</strong> <?php echo $course['SoTinChi']; ?></p>
                                <p><strong>Số Lượng Còn Lại:</strong> <?php echo $course['SoLuongSV']; ?></p>
                            </div>
                            <div class="card-footer">
                                <?php if ($course['isRegistered']): ?>
                                    <button class="btn btn-success" disabled>Đã Đăng Ký</button>
                                <?php elseif ($course['SoLuongSV'] <= 0): ?>
                                    <button class="btn btn-secondary" disabled>Hết Chỗ</button>
                                <?php else: ?>
                                    <a href="index.php?controller=course&action=register&id=<?php echo $course['MaHP']; ?>" class="btn btn-primary">Đăng Ký</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>