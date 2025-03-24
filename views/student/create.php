<?php include 'views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>THÊM SINH VIÊN</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=student&action=store" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="MaSV" class="form-label">Mã sinh viên</label>
                        <input type="text" class="form-control" id="MaSV" name="MaSV" required>
                    </div>
                    <div class="mb-3">
                        <label for="HoTen" class="form-label">Họ tên</label>
                        <input type="text" class="form-control" id="HoTen" name="HoTen" required>
                    </div>
                    <div class="mb-3">
                        <label for="GioiTinh" class="form-label">Giới tính</label>
                        <select class="form-select" id="GioiTinh" name="GioiTinh" required>
                            <option value="Nam">Nam</option>
                            <option value="Nữ">Nữ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="NgaySinh" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" required>
                    </div>
                    <div class="mb-3">
                        <label for="MaNganh" class="form-label">Ngành học</label>
                        <select class="form-select" id="MaNganh" name="MaNganh" required>
                            <?php foreach($departments as $department): ?>
                                <option value="<?php echo $department->MaNganh; ?>"><?php echo $department->TenNganh; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="Hinh" class="form-label">Hình ảnh</label>
                        <input type="file" class="form-control" id="Hinh" name="Hinh" accept="image/*" onchange="previewImage(event)">
                        <div class="form-text">Hỗ trợ: JPG, PNG, WEBP (Max: 2MB)</div>
                    </div>
                    <div class="mb-3 text-center">
                        <div class="border p-3 mt-3">
                            <img id="preview" src="uploads/default-avatar.jpg" alt="Preview" 
                                style="max-width: 100%; max-height: 250px; object-fit: contain;">
                        </div>
                        <div class="mt-2 text-muted">Xem trước ảnh</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Lưu</button>
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
    } else {
        preview.src = 'uploads/default-avatar.jpg';
    }
}
</script>

<?php include 'views/layouts/footer.php'; ?>
