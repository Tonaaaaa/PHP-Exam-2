<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Danh Sách Sinh Viên</h2>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="index.php?controller=student&action=create" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm Sinh Viên Mới
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Hình Ảnh</th>
                        <th>Họ Tên</th>
                        <th>Giới Tính</th>
                        <th>Ngày Sinh</th>
                        <th>Ngành Học</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Không tìm thấy sinh viên nào</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['MaSV']); ?></td>
                                <td>
                                    <?php if (!empty($student['Hinh'])): ?>
                                        <img src="/<?php echo htmlspecialchars($student['Hinh']); ?>" alt="Hình Sinh Viên" class="student-image" style="max-width: 50px; max-height: 50px;">
                                    <?php else: ?>
                                        <img src="/assets/images/default-avatar.jpg" alt="Hình Mặc Định" class="student-image" style="max-width: 50px; max-height: 50px;">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($student['HoTen']); ?></td>
                                <td><?php echo htmlspecialchars($student['GioiTinh']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($student['NgaySinh'])); ?></td>
                                <td><?php echo htmlspecialchars($student['TenNganh']); ?></td>
                                <td>
                                    <a href="index.php?controller=student&action=show&id=<?php echo urlencode($student['MaSV']); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="index.php?controller=student&action=edit&id=<?php echo urlencode($student['MaSV']); ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?controller=student&action=delete&id=<?php echo urlencode($student['MaSV']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="index.php?controller=student&action=index&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>