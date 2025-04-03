<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Thêm Sinh Viên Mới</h2>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="index.php?controller=student&action=store" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="hoTen" class="form-label">Họ và Tên</label>
                <input type="text" class="form-control" id="hoTen" name="hoTen" required>
            </div>

            <div class="mb-3">
                <label for="gioiTinh" class="form-label">Giới Tính</label>
                <select class="form-select" id="gioiTinh" name="gioiTinh" required>
                    <option value="">Chọn Giới Tính</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="ngaySinh" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" id="ngaySinh" name="ngaySinh" required>
            </div>

            <div class="mb-3">
                <label for="maNganh" class="form-label">Ngành Học</label>
                <select class="form-select" id="maNganh" name="maNganh" required>
                    <option value="">Chọn Ngành Học</option>
                    <?php foreach ($majors as $major): ?>
                        <option value="<?php echo $major['MaNganh']; ?>"><?php echo $major['TenNganh']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="hinh" class="form-label">Hình Ảnh</label>
                <input type="file" class="form-control" id="hinh" name="hinh" accept="image/*">
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?controller=student&action=index" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>