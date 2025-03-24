<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Sinh viên và Đăng ký Học phần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php">Quản Lý Sinh Viên</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=student&action=index">Sinh Viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=course&action=index">Học Phần</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=registration&action=listAll">Đăng Kí</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=auth&action=login">Đăng Nhập</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="index.php?controller=registration&action=listAll">Danh sách đăng ký</a>
                    </li> -->
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['student_id'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">Xin chào, <?php echo $_SESSION['student_name']; ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=auth&action=logout">Đăng Xuất</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?controller=auth&action=login">Đăng Nhập</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
