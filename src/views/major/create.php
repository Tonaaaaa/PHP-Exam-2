<?php ob_start(); ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Thêm Ngành Học Mới</h2>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="index.php?controller=major&action=store" method="POST">
            <div class="mb-3">
                <label for="maNganh" class="form-label">Mã Ngành</label>
                <input type="text" class="form-control" id="maNganh" name="maNganh" required>
            </div>

            <div class="mb-3">
                <label for="tenNganh" class="form-label">Tên Ngành</label>
                <input type="text" class="form-control" id="tenNganh" name="tenNganh" required>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="index.php?controller=major&action=index" class="btn btn-secondary">Hủy</a>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>