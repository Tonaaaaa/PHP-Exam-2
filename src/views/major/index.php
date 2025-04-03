<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Quản Lý Ngành Học</h2>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="index.php?controller=major&action=create" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm Ngành Học Mới
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Mã Ngành</th>
                        <th>Tên Ngành</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($majors)): ?>
                        <tr>
                            <td colspan="3" class="text-center">Không tìm thấy ngành học nào</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($majors as $major): ?>
                            <tr>
                                <td><?php echo $major['MaNganh']; ?></td>
                                <td><?php echo $major['TenNganh']; ?></td>
                                <td>
                                    <a href="index.php?controller=major&action=edit&id=<?php echo $major['MaNganh']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?controller=major&action=delete&id=<?php echo $major['MaNganh']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa ngành học này?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>