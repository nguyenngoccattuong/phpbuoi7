<?php include 'views/layouts/header.php'; ?>

<div class="container mt-4">
    <h1>DANH SÁCH TẤT CẢ HỌC PHẦN ĐÃ ĐĂNG KÝ</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">DANH SÁCH HỌC PHẦN ĐÃ ĐĂNG KÝ</h4>
            <span class="badge bg-light text-dark">Tổng số: <?php echo $totalCourseCount; ?> học phần</span>
        </div>
        <div class="card-body">
            <?php if (empty($allRegistrations)): ?>
                <div class="alert alert-info">
                    Chưa có học phần nào được đăng ký.
                </div>
            <?php else: ?>
                <?php foreach ($registrationsByMaDK as $maDK => $registrationData): ?>
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Mã ĐK: <?php echo $maDK; ?></strong> | 
                                Ngày: <?php echo date('d/m/Y', strtotime($registrationData['info']['NgayDK'])); ?>
                            </div>
                            <div class="col-md-6 text-md-end">
                                Sinh viên: <?php echo $registrationData['info']['HoTen']; ?> 
                                (<?php echo $registrationData['info']['MaSV']; ?>)
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã HP</th>
                                    <th>Tên Học Phần</th>
                                    <th>Số Tín Chỉ</th>
                                    <th>Số Lượng Còn Lại</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $index = 1;
                                $totalCredits = 0;
                                foreach ($registrationData['courses'] as $course): 
                                    $totalCredits += $course['SoTinChi'];
                                ?>
                                <tr>
                                    <td><?php echo $index++; ?></td>
                                    <td><?php echo $course['MaHP']; ?></td>
                                    <td><?php echo $course['TenHP']; ?></td>
                                    <td><?php echo $course['SoTinChi']; ?></td>
                                    <td><?php echo $course['SoLuong']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tổng tín chỉ:</td>
                                    <td colspan="2" class="fw-bold"><?php echo $totalCredits; ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="index.php?controller=course&action=index" class="btn btn-primary">Quay lại danh sách học phần</a>
        <a href="index.php" class="btn btn-secondary">Về trang chủ</a>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?> 