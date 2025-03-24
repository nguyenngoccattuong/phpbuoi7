<?php
class StudentController {
    private $studentModel;
    
    public function __construct() {
        $this->studentModel = new Student();
    }
    
    // Display list of students
    public function index() {
        $students = $this->studentModel->getStudents();
        include 'views/student/index.php';
    }
    
    // Display student details
    public function detail($id) {
        $student = $this->studentModel->getStudentById($id);
        include 'views/student/detail.php';
    }
    
    // Display create form
    public function create() {
        $departments = $this->studentModel->getDepartments();
        include 'views/student/create.php';
    }
    
    // Process create form
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Khởi tạo biến $imagePath với ảnh mặc định
            $imagePath = 'uploads/default-avatar.jpg';
            
            // Xử lý upload ảnh nếu có
            if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
                // Lấy thông tin file
                $fileName = $_FILES['Hinh']['name'];
                $fileTmpName = $_FILES['Hinh']['tmp_name'];
                $fileSize = $_FILES['Hinh']['size'];
                $fileType = $_FILES['Hinh']['type'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                // Kiểm tra định dạng file
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (in_array($fileExt, $allowedExtensions)) {
                    // Kiểm tra kích thước file (2MB = 2097152 bytes)
                    if ($fileSize <= 2097152) {
                        // Tạo tên file ngẫu nhiên để tránh trùng lặp
                        $newFileName = uniqid('student_', true) . '.' . $fileExt;
                        $uploadPath = 'uploads/' . $newFileName;
                        
                        // Di chuyển file upload vào thư mục đích
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            $imagePath = $uploadPath;
                        } else {
                            $_SESSION['error'] = 'Lỗi khi tải ảnh lên. Vui lòng thử lại.';
                        }
                    } else {
                        $_SESSION['error'] = 'Kích thước ảnh quá lớn. Vui lòng chọn ảnh dưới 2MB.';
                    }
                } else {
                    $_SESSION['error'] = 'Chỉ chấp nhận các định dạng ảnh: JPG, PNG, WEBP và GIF.';
                }
            }
            
            // Tạo mảng dữ liệu
            $data = [
                'MaSV' => $_POST['MaSV'],
                'HoTen' => $_POST['HoTen'],
                'GioiTinh' => $_POST['GioiTinh'],
                'NgaySinh' => $_POST['NgaySinh'],
                'Hinh' => $imagePath,
                'MaNganh' => $_POST['MaNganh']
            ];
            
            // Gọi model để thêm sinh viên
            if ($this->studentModel->addStudent($data)) {
                $_SESSION['message'] = 'Thêm sinh viên thành công!';
                header('Location: index.php?controller=student&action=index');
                exit;
            } else {
                $_SESSION['error'] = 'Thêm sinh viên thất bại. Vui lòng thử lại.';
                $departments = $this->studentModel->getDepartments();
                include 'views/student/create.php';
            }
        } else {
            // Hiển thị form thêm sinh viên
            $departments = $this->studentModel->getDepartments();
            include 'views/student/create.php';
        }
    }
    
    // Display edit form
    public function edit($id) {
        $student = $this->studentModel->getStudentById($id);
        $departments = $this->studentModel->getDepartments();
        include 'views/student/edit.php';
    }
    
    // Process edit form
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy đường dẫn ảnh hiện tại
            $imagePath = $_POST['current_image'];
            
            // Xử lý upload ảnh mới nếu có
            if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
                // Lấy thông tin file
                $fileName = $_FILES['Hinh']['name'];
                $fileTmpName = $_FILES['Hinh']['tmp_name'];
                $fileSize = $_FILES['Hinh']['size'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                // Kiểm tra định dạng file
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (in_array($fileExt, $allowedExtensions)) {
                    // Kiểm tra kích thước file (2MB = 2097152 bytes)
                    if ($fileSize <= 2097152) {
                        // Tạo tên file ngẫu nhiên để tránh trùng lặp
                        $newFileName = uniqid('student_', true) . '.' . $fileExt;
                        $uploadPath = 'uploads/' . $newFileName;
                        
                        // Di chuyển file upload vào thư mục đích
                        if (move_uploaded_file($fileTmpName, $uploadPath)) {
                            $imagePath = $uploadPath;
                            
                            // Xóa ảnh cũ nếu có
                            if (!empty($_POST['current_image']) && file_exists($_POST['current_image']) && $_POST['current_image'] != 'uploads/default-avatar.jpg') {
                                @unlink($_POST['current_image']);
                            }
                        } else {
                            $_SESSION['error'] = 'Lỗi khi tải ảnh lên. Vui lòng thử lại.';
                        }
                    } else {
                        $_SESSION['error'] = 'Kích thước ảnh quá lớn. Vui lòng chọn ảnh dưới 2MB.';
                    }
                } else {
                    $_SESSION['error'] = 'Chỉ chấp nhận các định dạng ảnh: JPG, PNG, WEBP và GIF.';
                }
            }
            
            // Tạo mảng dữ liệu
            $data = [
                'MaSV' => $_POST['MaSV'],
                'HoTen' => $_POST['HoTen'],
                'GioiTinh' => $_POST['GioiTinh'],
                'NgaySinh' => $_POST['NgaySinh'],
                'Hinh' => $imagePath,
                'MaNganh' => $_POST['MaNganh']
            ];
            
            // Gọi model để cập nhật sinh viên
            if ($this->studentModel->updateStudent($data)) {
                $_SESSION['message'] = 'Cập nhật sinh viên thành công!';
                header('Location: index.php?controller=student&action=index');
                exit;
            } else {
                $_SESSION['error'] = 'Cập nhật sinh viên thất bại. Vui lòng thử lại.';
                // Lấy lại thông tin sinh viên và ngành học
                $student = $this->studentModel->getStudentById($_POST['MaSV']);
                $departments = $this->studentModel->getDepartments();
                include 'views/student/edit.php';
            }
        } else {
            $id = isset($_GET['id']) ? $_GET['id'] : die('ID không hợp lệ');
            
            // Lấy thông tin sinh viên
            $student = $this->studentModel->getStudentById($id);
            $departments = $this->studentModel->getDepartments();
            
            // Hiển thị form chỉnh sửa
            include 'views/student/edit.php';
        }
    }
    
    // Delete student
    public function delete() {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ID không hợp lệ');
        
        // Lấy thông tin sinh viên
        $student = $this->studentModel->getStudentById($id);
        
        // Hiển thị trang xác nhận xóa
        include 'views/student/delete.php';
    }

    // Thêm phương thức destroy
    public function destroy() {
        $id = isset($_GET['id']) ? $_GET['id'] : die('ID không hợp lệ');
        
        // Lấy thông tin sinh viên trước khi xóa (để xóa file ảnh)
        $student = $this->studentModel->getStudentById($id);
        
        // Thực hiện xóa
        if ($this->studentModel->deleteStudent($id)) {
            // Xóa file ảnh nếu có
            if (!empty($student->Hinh) && file_exists($student->Hinh) && $student->Hinh != 'uploads/default-avatar.jpg') {
                @unlink($student->Hinh);
            }
            
            $_SESSION['message'] = 'Xóa sinh viên thành công!';
        } else {
            $_SESSION['error'] = 'Xóa sinh viên thất bại. Vui lòng thử lại.';
        }
        
        // Chuyển hướng về trang danh sách
        header('Location: index.php?controller=student&action=index');
        exit;
    }
}
?>
