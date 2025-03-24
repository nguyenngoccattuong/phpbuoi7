<?php include 'views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>ĐĂNG KÝ HỌC PHẦN</h2>
    </div>
    <div class="card-body">
        <form action="index.php?controller=registration&action=store" method="post">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Mã Học Phần</th>
                            <th>Tên Học Phần</th>
                            <th>Số Tín Chỉ</th>
                            <th>Số lượng dự kiến</th>
                            <th>Chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($courses as $course): ?>
                        <tr>
                            <td><?php echo $course->MaHP; ?></td>
                            <td><?php echo $course->TenHP; ?></td>
                            <td><?php echo $course->SoTinChi; ?></td>
                            <td><?php echo isset($course->SoLuong) ? $course->SoLuong : 'N/A'; ?></td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="courses[]" value="<?php echo $course->MaHP; ?>" id="course_<?php echo $course->MaHP; ?>" 
                                        <?php echo (isset($course->SoLuong) && $course->SoLuong <= 0) ? 'disabled' : ''; ?>>
                                    <label class="form-check-label" for="course_<?php echo $course->MaHP; ?>">
                                        <?php echo (isset($course->SoLuong) && $course->SoLuong <= 0) ? 'Hết chỗ' : 'Đăng ký'; ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <button type="submit" class="btn btn-success">Đăng ký</button>
                <a href="index.php?controller=course&action=index" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
