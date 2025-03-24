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
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4>DANH SÁCH HỌC PHẦN ĐÃ ĐĂNG KÝ</h4>
        </div>
        <div class="card-body">
            <?php if (empty($registrations)): ?>
                <div class="alert alert-info">
                    Bạn chưa đăng ký học phần nào.
                </div>
            <?php else: ?>
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Mã Đăng Ký</th>
                            <th>Ngày Đăng Ký</th>
                            <th>Mã HP</th>
                            <th>Tên Học Phần</th>
                            <th>Số Tín Chỉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalCredits = 0; 
                        $prevMaDK = null;
                        foreach ($registrations as $index => $registration): 
                            $totalCredits += $registration->SoTinChi;
                            $rowClass = ($prevMaDK !== $registration->MaDK && $index > 0) ? 'border-top border-primary' : '';
                            $prevMaDK = $registration->MaDK;
                        ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td><?php echo $registration->MaDK; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($registration->NgayDK)); ?></td>
                            <td><?php echo $registration->MaHP; ?></td>
                            <td><?php echo $registration->TenHP; ?></td>
                            <td><?php echo $registration->SoTinChi; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Tổng số tín chỉ:</td>
                            <td class="fw-bold"><?php echo $totalCredits; ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <p>
        <a href="index.php?controller=course&action=index" class="btn btn-primary">Đăng ký thêm học phần</a>
        <a href="index.php" class="btn btn-secondary">Về trang chủ</a>
    </p>
    
    <?php if (!empty($_SESSION['registered_courses'])): ?>
    <div class="card mt-4">
        <div class="card-header bg-warning text-dark">
            <h4>Học phần đang chọn (chưa đăng ký)</h4>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Bạn có học phần đang chọn nhưng chưa xác nhận đăng ký!
            </div>
            <a href="index.php?controller=registration&action=register" class="btn btn-success">
                Tiếp tục đăng ký
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
