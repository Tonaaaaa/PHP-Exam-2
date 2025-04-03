<?php ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Đăng Nhập Sinh Viên</h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="index.php?controller=auth&action=login" method="POST">
                    <div class="mb-3">
                        <label for="maSV" class="form-label">Mã Sinh Viên</label>
                        <input type="text" class="form-control" id="maSV" name="maSV" value="2180608118" required>
                    </div>

                    <div class="mb-3">
                        <label for="matKhau" class="form-label">Mật Khẩu</label>
                        <input type="password" class="form-control" id="matKhau" name="matKhau" value="123456" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>