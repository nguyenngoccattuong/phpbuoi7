<?php include 'views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-danger text-white">
        <h2>XÁC NHẬN XÓA SINH VIÊN</h2>
    </div>
    <div class="card-body">
        <div class="alert alert-danger">
            <h4 class="alert-heading">Cảnh báo!</h4>
            <p>Bạn sắp xóa sinh viên <strong><?php echo $student->HoTen; ?></strong>. Hành động này không thể hoàn tác.</p>
            <p>Bạn có chắc chắn muốn tiếp tục không?</p>
        </div>
        
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="border p-3">
                    <img src="<?php echo !empty($student->Hinh) ? $student->Hinh : 'uploads/default-avatar.jpg'; ?>" 
                         alt="<?php echo $student->HoTen; ?>" 
                         class="img-fluid mb-3" style="max-height: 250px; object-fit: contain;">
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
            </div>
        </div>
        
        <div class="mt-4">
            <form action="index.php?controller=student&action=destroy&id=<?php echo $student->MaSV; ?>" method="post">
                <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                <a href="index.php?controller=student&action=index" class="btn btn-secondary">Hủy bỏ</a>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
