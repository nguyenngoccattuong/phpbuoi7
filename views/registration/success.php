<?php include 'views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header bg-success text-white">
        <h2>THÔNG TIN HỌC PHẦN ĐÃ LƯU</h2>
    </div>
    <div class="card-body">
        <div class="alert alert-success mb-4">
            <h4 class="alert-heading">Đăng ký thành công!</h4>
            <p>Bạn đã đăng ký học phần thành công. Dưới đây là thông tin chi tiết về đăng ký của bạn.</p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin đăng ký</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <th>Mã đăng ký:</th>
                                <td><?php echo $registration->MaDK; ?></td>
                            </tr>
                            <tr>
                                <th>Ngày đăng ký:</th>
                                <td><?php echo date('d/m/Y', strtotime($registration->NgayDK)); ?></td>
                            </tr>
                            <tr>
                                <th>Mã sinh viên:</th>
                                <td><?php echo $registration->MaSV; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Học phần đã đăng ký</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>MaHP</th>
                                    <th>Tên Học Phần</th>
                                    <th>Số Tín Chỉ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalCredits = 0;
                                foreach($registrationDetails as $detail): 
                                    $totalCredits += $detail->SoTinChi;
                                ?>
                                <tr>
                                    <td><?php echo $detail->MaHP; ?></td>
                                    <td><?php echo $detail->TenHP; ?></td>
                                    <td><?php echo $detail->SoTinChi; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Tổng số tín chỉ:</td>
                                    <td><?php echo $totalCredits; ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="index.php" class="btn btn-primary">Về trang chủ</a>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
