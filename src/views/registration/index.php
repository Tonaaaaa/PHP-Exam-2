<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Học Phần Đã Đăng Ký</h2>
    </div>
    <div class="card-body">
        <?php if (empty($registrations) || !array_filter($registrations, function ($reg) {
            return !empty($reg['MaHP']);
        })): ?>
            <div class="alert alert-info">Bạn chưa đăng ký học phần nào.</div>
            <a href="index.php?controller=course&action=index" class="btn btn-primary">Xem Danh Sách Học Phần</a>
        <?php else: ?>
            <div class="mb-3">
                <a href="index.php?controller=registration&action=cancelAll" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy tất cả đăng ký học phần?')">
                    <i class="fas fa-trash"></i> Hủy Tất Cả Đăng Ký
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Ngày Đăng Ký</th>
                            <th>Mã Học Phần</th>
                            <th>Tên Học Phần</th>
                            <th>Số Tín Chỉ</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $registration): ?>
                            <?php if (!empty($registration['MaHP'])): // Chỉ hiển thị nếu có MaHP 
                            ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($registration['NgayDK'])); ?></td>
                                    <td><?php echo htmlspecialchars($registration['MaHP']); ?></td>
                                    <td><?php echo htmlspecialchars($registration['TenHP'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($registration['SoTinChi'] ?? 'N/A'); ?></td>
                                    <td>
                                        <a href="index.php?controller=registration&action=cancelCourse&maDK=<?php echo urlencode($registration['MaDK']); ?>&maHP=<?php echo urlencode($registration['MaHP']); ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký học phần này?')">
                                            <i class="fas fa-times"></i> Hủy
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="index.php?controller=course&action=index" class="btn btn-primary">Đăng Ký Thêm Học Phần</a>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>