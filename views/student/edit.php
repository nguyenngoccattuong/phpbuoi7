<?php include 'views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>CHỈNH SỬA SINH VIÊN</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=student&action=update" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MaSV" value="<?php echo $student->MaSV; ?>">
            <input type="hidden" name="current_image" value="<?php echo $student->Hinh; ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="MaSV" class="form-label">Mã sinh viên</label>
                        <input type="text" class="form-control" value="<?php echo $student->MaSV; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="HoTen" class="form-label">Họ tên</label>
                        <input type="text" class="form-control" id="HoTen" name="HoTen" value="<?php echo $student->HoTen; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="GioiTinh" class="form-label">Giới tính</label>
                        <select class="form-select" id="GioiTinh" name="GioiTinh" required>
                            <option value="Nam" <?php echo ($student->GioiTinh == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                            <option value="Nữ" <?php echo ($student->GioiTinh == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="NgaySinh" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" value="<?php echo date('Y-m-d', strtotime($student->NgaySinh)); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="MaNganh" class="form-label">Ngành học</label>
                        <select class="form-select" id="MaNganh" name="MaNganh" required>
                            <?php foreach($departments as $department): ?>
                                <option value="<?php echo $department->MaNganh; ?>" <?php echo ($student->MaNganh == $department->MaNganh) ? 'selected' : ''; ?>>
                                    <?php echo $department->TenNganh; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="Hinh" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" id="Hinh" name="Hinh" accept="image/*" onchange="previewImage(event)">
                        <div class="form-text">Để trống nếu không muốn thay đổi ảnh</div>
                    </div>
                    <div class="mb-3 text-center">
                        <div class="border p-3 mt-3">
                            <img id="preview" src="<?php echo !empty($student->Hinh) ? $student->Hinh : 'uploads/default-avatar.jpg'; ?>" 
                                alt="<?php echo $student->HoTen; ?>" style="max-width: 100%; max-height: 250px; object-fit: contain;">
                        </div>
                        <div class="mt-2 text-muted">Ảnh hiện tại</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                <a href="index.php?controller=student&action=index" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    var preview = document.getElementById('preview');
    var file = event.target.files[0];
    
    if (file) {
        var reader = new FileReader();
        
        reader.onload = function() {
            preview.src = reader.result;
        }
        
        reader.readAsDataURL(file);
    }
}
</script>

<?php include 'views/layouts/footer.php'; ?>