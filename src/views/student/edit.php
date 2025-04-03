<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Chỉnh Sửa Thông Tin Sinh Viên</h2>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="index.php?controller=student&action=update" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="maSV" class="form-label">Mã Sinh Viên</label>
                <input type="text" class="form-control" id="maSV" name="maSV" value="<?php echo $student['MaSV']; ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="hoTen" class="form-label">Họ Tên</label>
                <input type="text" class="form-control" id="hoTen" name="hoTen" value="<?php echo $student['HoTen']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="gioiTinh" class="form-label">Giới Tính</label>
                <select class="form-select" id="gioiTinh" name="gioiTinh" required>
                    <option value="">Chọn Giới Tính</option>
                    <option value="Nam" <?php echo ($student['GioiTinh'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                    <option value="Nữ" <?php echo ($student['GioiTinh'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="ngaySinh" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" id="ngaySinh" name="ngaySinh" value="<?php echo $student['NgaySinh']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="maNganh" class="form-label">Ngành Học</label>
                <select class="form-select" id="maNganh" name="maNganh" required>
                    <option value="">Chọn Ngành Học</option>
                    <?php foreach ($majors as $major): ?>
                        <option value="<?php echo $major['MaNganh']; ?>" <?php echo ($student['MaNganh'] == $major['MaNganh']) ? 'selected' : ''; ?>><?php echo $major['TenNganh']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="matKhau" class="form-label">Mật Khẩu Mới</label>
                <input type="password" class="form-control" id="matKhau" name="matKhau">
                <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
            </div>

            <div class="mb-3">
                <label for="currentImage" class="form-label">Hình Ảnh Hiện Tại</label>
                <div>
                    <?php if (!empty($student['Hinh'])): ?>
                        <img src="<?php echo $student['Hinh']; ?>" alt="Hình Sinh Viên" class="student-image">
                    <?php else: ?>
                        <p>Chưa có hình ảnh</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="hinh" class="form-label">Hình Ảnh Mới</label>
                <input type="file" class="form-control" id="hinh" name="hinh" accept="image/*">
                <small class="text-muted">Để trống nếu không muốn thay đổi hình ảnh</small>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?controller=student&action=index" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>