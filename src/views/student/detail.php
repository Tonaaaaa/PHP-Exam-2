<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Thông Tin Chi Tiết Sinh Viên</h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <?php if (!empty($student['Hinh'])): ?>
                    <img src="/<?php echo htmlspecialchars($student['Hinh']); ?>" alt="Hình Sinh Viên" class="img-fluid rounded" style="max-height: 300px;">
                <?php else: ?>
                    <img src="/assets/images/default-avatar.jpg" alt="Hình Mặc Định" class="img-fluid rounded" style="max-height: 300px;">
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <table class="table">
                    <tr>
                        <th>Mã Sinh Viên:</th>
                        <td><?php echo htmlspecialchars($student['MaSV']); ?></td>
                    </tr>
                    <tr>
                        <th>Họ Tên:</th>
                        <td><?php echo htmlspecialchars($student['HoTen']); ?></td>
                    </tr>
                    <tr>
                        <th>Giới Tính:</th>
                        <td><?php echo htmlspecialchars($student['GioiTinh']); ?></td>
                    </tr>
                    <tr>
                        <th>Ngày Sinh:</th>
                        <td><?php echo date('d/m/Y', strtotime($student['NgaySinh'])); ?></td>
                    </tr>
                    <tr>
                        <th>Ngành Học:</th>
                        <td><?php echo htmlspecialchars($student['TenNganh']); ?></td>
                    </tr>
                </table>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="index.php?controller=student&action=index" class="btn btn-secondary">Quay Lại</a>
                    <a href="index.php?controller=student&action=edit&id=<?php echo urlencode($student['MaSV']); ?>" class="btn btn-warning">Chỉnh Sửa</a>
                    <a href="index.php?controller=student&action=delete&id=<?php echo urlencode($student['MaSV']); ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">Xóa</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>