<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <h1>ĐĂNG KÝ HỌC PHẦN</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <!-- PHẦN 1: HIỂN THỊ HỌC PHẦN ĐÃ ĐĂNG KÝ -->
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
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4>Danh sách học phần đã chọn</h4>
        </div>
        <div class="card-body">
            <?php if (empty($registrationDetails)): ?>
                <div class="alert alert-info">
                    Chưa có học phần nào được đăng ký.
                </div>
            <?php else: ?>
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
                            <td colspan="2" class="text-end fw-bold">Số học phần: <?php echo count($registrationDetails); ?></td>
                            <td colspan="2" class="fw-bold">Tổng số tín chỉ: <?php echo $totalCredits; ?></td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="d-flex justify-content-between mt-3">
                    <a href="index.php?controller=registration&action=removeAll" 
                       class="btn btn-warning"
                       onclick="return confirm('Bạn có chắc muốn xóa tất cả học phần?')">
                        Xóa Đăng Ký
                    </a>
                    <a href="index.php?controller=registration&action=confirm" 
                       class="btn btn-success">
                        Lưu đăng ký
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- PHẦN 2: FORM ĐĂNG KÝ HỌC PHẦN MỚI -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h4>Chọn học phần đăng ký</h4>
        </div>
        <div class="card-body">
            <form action="index.php?controller=registration&action=register" method="post">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã Học Phần</th>
                            <th>Tên Học Phần</th>
                            <th>Số Tín Chỉ</th>
                            <th>Số lượng dự kiến</th>
                            <th>Chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo $course->MaHP; ?></td>
                            <td><?php echo $course->TenHP; ?></td>
                            <td><?php echo $course->SoTinChi; ?></td>
                            <td><?php echo $course->SoLuong; ?></td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="selected_courses[]" 
                                           value="<?php echo $course->MaHP; ?>" 
                                           id="course_<?php echo $course->MaHP; ?>"
                                           <?php echo in_array($course->MaHP, $registeredCourses) ? 'disabled checked' : ''; ?>>
                                    <label class="form-check-label" for="course_<?php echo $course->MaHP; ?>">
                                        Đăng ký
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Đăng ký</button>
                    <a href="index.php?controller=course&action=index" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
