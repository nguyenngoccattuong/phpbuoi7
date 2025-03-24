<?php include 'views/layouts/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h2>XÁC NHẬN ĐĂNG KÝ HỌC PHẦN</h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-7">
                <h4>Danh sách học phần đăng ký</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>MaHP</th>
                            <th>Tên Học Phần</th>
                            <th>Số Chỉ Chỉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalCredits = 0;
                        $courseCount = 0;
                        foreach($registrationDetails as $detail): 
                            $totalCredits += $detail->SoTinChi;
                            $courseCount++;
                        ?>
                        <tr>
                            <td><?php echo $detail->MaHP; ?></td>
                            <td><?php echo $detail->TenHP; ?></td>
                            <td><?php echo $detail->SoTinChi; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-info">
                            <td colspan="2" class="text-end fw-bold">Số lượng học phần:</td>
                            <td><?php echo $courseCount; ?></td>
                        </tr>
                        <tr class="table-info">
                            <td colspan="2" class="text-end fw-bold">Tổng số tín chỉ:</td>
                            <td><?php echo $totalCredits; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Thông tin Đăng ký</h4>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-5">Mã số sinh viên:</dt>
                            <dd class="col-sm-7"><?php echo $student->MaSV; ?></dd>
                            
                            <dt class="col-sm-5">Họ Tên sinh viên:</dt>
                            <dd class="col-sm-7"><?php echo $student->HoTen; ?></dd>
                            
                            <dt class="col-sm-5">Ngày Sinh:</dt>
                            <dd class="col-sm-7"><?php echo date('d/m/Y', strtotime($student->NgaySinh)); ?></dd>
                            
                            <dt class="col-sm-5">Ngành Học:</dt>
                            <dd class="col-sm-7"><?php echo $student->TenNganh; ?></dd>
                            
                            <dt class="col-sm-5">Ngày Đăng Kí:</dt>
                            <dd class="col-sm-7"><?php echo date('d/m/Y'); ?></dd>
                        </dl>
                    </div>
                </div>
                
                <form action="index.php?controller=registration&action=confirm" method="post" class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg w-100">Xác Nhận</button>
                    <a href="index.php?controller=registration&action=view" class="btn btn-secondary w-100 mt-2">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
