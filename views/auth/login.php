<?php include 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h2>ĐĂNG NHẬP</h2>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                            echo $_SESSION['login_error']; 
                            unset($_SESSION['login_error']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <form action="index.php?controller=auth&action=authenticate" method="post">
                    <div class="mb-3">
                        <label for="MaSV" class="form-label">MaSV</label>
                        <input type="text" class="form-control" id="MaSV" name="MaSV" required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                    </div>
                    <div class="mb-3">
                        <a href="index.php">Back to List</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
