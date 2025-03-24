<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <h1>ĐĂNG KÝ HỌC PHẦN</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php 
    // Lấy học phần đã đăng ký từ session
    $registeredCourses = isset($_SESSION['registered_courses']) ? $_SESSION['registered_courses'] : [];
    $registrationDetails = [];
    $totalCredits = 0;
    
    // Lấy thông tin chi tiết của từng học phần
    if (!empty($registeredCourses)) {
        foreach ($registeredCourses as $courseId) {
            $course = $this->courseModel->getCourseById($courseId);
            if ($course) {
                $registrationDetails[] = $course;
                $totalCredits += $course->SoTinChi;
            }
        }
    }
    ?>
    
    <?php if (empty($registrationDetails)): ?>
        <div class="alert alert-info">
            Chưa có học phần đăng ký.
        </div>
        <p>
            <a href="index.php?controller=course&action=index" class="btn btn-primary">Quay lại danh sách học phần</a>
        </p>
    <?php else: ?>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>Danh sách học phần đã chọn</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã HP</th>
                            <th>Tên học phần</th>
                            <th>Số tín chỉ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrationDetails as $course): ?>
                        <tr>
                            <td><?php echo $course->MaHP; ?></td>
                            <td><?php echo $course->TenHP; ?></td>
                            <td><?php echo $course->SoTinChi; ?></td>
                            <td>
                                <a href="index.php?controller=registration&action=removeItem&id=<?php echo $course->MaHP; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Bạn có chắc muốn xóa học phần này?')">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end fw-bold">Tổng số tín chỉ:</td>
                            <td colspan="2" class="fw-bold"><?php echo $totalCredits; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <div>
                    <a href="index.php?controller=course&action=index" class="btn btn-secondary">Quay lại</a>
                    <a href="index.php?controller=registration&action=removeAll" 
                       class="btn btn-warning"
                       onclick="return confirm('Bạn có chắc muốn xóa tất cả học phần?')">
                        Xóa tất cả
                    </a>
                </div>
                <a href="index.php?controller=registration&action=confirm" class="btn btn-success">Xác nhận đăng ký</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
