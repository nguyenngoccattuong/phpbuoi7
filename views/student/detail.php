<?php include 'views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>THÔNG TIN CHI TIẾT SINH VIÊN</h2>
        <div>
            <a href="index.php?controller=student&action=edit&id=<?php echo $student->MaSV; ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Chỉnh sửa
            </a>
            <a href="index.php?controller=student&action=index" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="border p-3">
                    <img src="<?php echo !empty($student->Hinh) ? $student->Hinh : 'uploads/default-avatar.jpg'; ?>" 
                         alt="<?php echo $student->HoTen; ?>" 
                         class="img-fluid mb-3" style="max-height: 300px; object-fit: contain;">
                </div>
            </div>
            <div class="col-md-8">
                <table class="table">
                    <tr>
                        <th style="width: 30%">Mã sinh viên:</th>
                        <td><?php echo $student->MaSV; ?></td>
                    </tr>
                    <tr>
                        <th>Họ tên:</th>
                        <td><?php echo $student->HoTen; ?></td>
                    </tr>
                    <tr>
                        <th>Giới tính:</th>
                        <td><?php echo $student->GioiTinh; ?></td>
                    </tr>
                    <tr>
                        <th>Ngày sinh:</th>
                        <td><?php echo date('d/m/Y', strtotime($student->NgaySinh)); ?></td>
                    </tr>
                    <tr>
                        <th>Ngành học:</th>
                        <td><?php echo $student->TenNganh; ?></td>
                    </tr>
                </table>
                
                <div class="mt-4">
                    <a href="index.php?controller=student&action=index" class="btn btn-secondary">Danh sách sinh viên</a>
                    <a href="index.php?controller=student&action=edit&id=<?php echo $student->MaSV; ?>" class="btn btn-warning">Chỉnh sửa</a>
                    <a href="index.php?controller=student&action=delete&id=<?php echo $student->MaSV; ?>" class="btn btn-danger" 
                       onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">Xóa</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
