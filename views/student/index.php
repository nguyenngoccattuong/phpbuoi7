<?php
$title = 'Quản lý Sinh Viên';
ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>TRANG SINH VIÊN</h2>
        <a href="index.php?controller=student&action=create" class="btn btn-primary">Thêm sinh viên</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>MaSV</th>
                        <th>HoTen</th>
                        <th>GioiTinh</th>
                        <th>NgaySinh</th>
                        <th>Hinh</th>
                        <th>MaNganh</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($students as $student): ?>
                    <tr>
                        <td><?php echo $student->MaSV; ?></td>
                        <td><?php echo $student->HoTen; ?></td>
                        <td><?php echo $student->GioiTinh; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($student->NgaySinh)); ?></td>
                        <td><img src="<?php echo $student->Hinh; ?>" class="img-thumbnail" width="50" height="50"></td>
                        <td><?php echo $student->TenNganh; ?></td>
                        <td>
                            <a href="index.php?controller=student&action=edit&id=<?php echo $student->MaSV; ?>" class="btn btn-sm btn-warning">Thêm</a>
                            <a href="index.php?controller=student&action=detail&id=<?php echo $student->MaSV; ?>" class="btn btn-sm btn-info">Sửa</a>
                            <a href="index.php?controller=student&action=delete&id=<?php echo $student->MaSV; ?>" class="btn btn-sm btn-danger">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once 'views/layouts/layout.php';
?>
