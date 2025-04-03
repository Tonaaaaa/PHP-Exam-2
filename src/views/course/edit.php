<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Chỉnh Sửa Học Phần</h2>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="index.php?controller=course&action=update" method="POST">
            <div class="mb-3">
                <label for="maHP" class="form-label">Mã Học Phần</label>
                <input type="text" class="form-control" id="maHP" name="maHP" value="<?php echo $course['MaHP']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="tenHP" class="form-label">Tên Học Phần</label>
                <input type="text" class="form-control" id="tenHP" name="tenHP" value="<?php echo $course['TenHP']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="soTinChi" class="form-label">Số Tín Chỉ</label>
                <input type="number" class="form-control" id="soTinChi" name="soTinChi" value="<?php echo $course['SoTinChi']; ?>" min="1" max="10" required>
            </div>

            <div class="mb-3">
                <label for="soLuongSV" class="form-label">Số Lượng Sinh Viên</label>
                <input type="number" class="form-control" id="soLuongSV" name="soLuongSV" value="<?php echo $course['SoLuongSV']; ?>" min="0" required>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?controller=course&action=manage" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>