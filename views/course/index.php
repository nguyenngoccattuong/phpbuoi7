<?php include 'views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>DANH SÁCH HỌC PHẦN</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Mã Học Phần</th>
                        <th>Tên Học Phần</th>
                        <th>Số Tín Chỉ</th>
                        <th>Số lượng dự kiến</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($courses as $course): ?>
                    <tr>
                        <td><?php echo $course->MaHP; ?></td>
                        <td><?php echo $course->TenHP; ?></td>
                        <td><?php echo $course->SoTinChi; ?></td>
                        <td><?php echo $course->SoLuong ?? 'N/A'; ?></td>
                        <td>
                            <?php if(isset($_SESSION['student_id'])): ?>
                                <?php if(isset($course->SoLuong) && $course->SoLuong > 0): ?>
                                    <a href="index.php?controller=registration&action=addItem&id=<?php echo $course->MaHP; ?>" class="btn btn-success btn-sm">Đăng Ký</a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>Hết chỗ</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="index.php?controller=auth&action=login" class="btn btn-primary btn-sm">Đăng nhập để đăng ký</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
