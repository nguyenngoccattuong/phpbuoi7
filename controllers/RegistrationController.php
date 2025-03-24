<?php
class RegistrationController {
    private $registrationModel;
    private $courseModel;
    private $studentModel;
    
    public function __construct() {
        $this->registrationModel = new Registration();
        $this->courseModel = new Course();
        $this->studentModel = new Student();
    }
    
    // Display registration form
    public function register() {
        // Lấy danh sách học phần
        $courses = $this->courseModel->getAllCourses();
        
        // Xử lý khi form được submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra xem có học phần nào được chọn không
            if (!isset($_POST['selected_courses']) || empty($_POST['selected_courses'])) {
                $_SESSION['error'] = 'Vui lòng chọn ít nhất một học phần';
                include 'views/registration/register.php';
                return;
            }
            
            // Khởi tạo mảng đăng ký trong session nếu chưa có
            if (!isset($_SESSION['registered_courses'])) {
                $_SESSION['registered_courses'] = [];
            }
            
            // Lấy danh sách học phần đã chọn
            $selectedCourses = $_POST['selected_courses'];
            
            // Thêm các học phần được chọn vào session
            foreach ($selectedCourses as $courseId) {
                if (!in_array($courseId, $_SESSION['registered_courses'])) {
                    $_SESSION['registered_courses'][] = $courseId;
                }
            }
            
            $_SESSION['message'] = 'Đăng ký học phần thành công!';
            header('Location: index.php?controller=registration&action=view');
            exit;
        }
        
        // Hiển thị form đăng ký
        include 'views/registration/register.php';
    }
    
    // Process registration
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if student is logged in
            if(!isset($_SESSION['student_id'])) {
                header('Location: index.php?controller=auth&action=login');
                return;
            }
            
            $studentId = $_SESSION['student_id'];
            $selectedCourses = isset($_POST['courses']) ? $_POST['courses'] : [];
            
            if(empty($selectedCourses)) {
                // No courses selected
                $_SESSION['message'] = 'Vui lòng chọn ít nhất một học phần để đăng ký.';
                header('Location: index.php?controller=registration&action=register');
                return;
            }
            
            // Create registration
            $registrationId = $this->registrationModel->createRegistration($studentId, $selectedCourses);
            
            if($registrationId) {
                $_SESSION['message'] = 'Đăng ký học phần thành công!';
                header('Location: index.php?controller=registration&action=view');
            } else {
                $_SESSION['message'] = 'Có lỗi xảy ra khi đăng ký học phần.';
                header('Location: index.php?controller=registration&action=register');
            }
        } else {
            header('Location: index.php?controller=registration&action=register');
        }
    }
    
    // View registrations
    public function view() {
        // Check if student is logged in
        if(!isset($_SESSION['student_id'])) {
            header('Location: index.php?controller=auth&action=login');
            return;
        }
        
        $studentId = $_SESSION['student_id'];
        $registrations = $this->registrationModel->getRegistrations($studentId);
        include 'views/registration/view.php';
    }
    
    // Delete registration
    public function delete() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check if student is logged in
            if(!isset($_SESSION['student_id'])) {
                header('Location: index.php?controller=auth&action=login');
                return;
            }
            
            $registrationId = isset($_POST['registration_id']) ? $_POST['registration_id'] : null;
            
            if(!$registrationId) {
                $_SESSION['message'] = 'Không xác định được đăng ký cần xóa.';
                header('Location: index.php?controller=registration&action=view');
                return;
            }
            
            if($this->registrationModel->deleteRegistration($registrationId)) {
                $_SESSION['message'] = 'Xóa đăng ký thành công!';
            } else {
                $_SESSION['message'] = 'Có lỗi xảy ra khi xóa đăng ký.';
            }
            
            header('Location: index.php?controller=registration&action=view');
        } else {
            header('Location: index.php?controller=registration&action=view');
        }
    }

    public function confirm() {
        // Kiểm tra nếu có dữ liệu được gửi lên từ form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy thông tin sinh viên
            $studentId = $_SESSION['student_id'];
            $student = $this->studentModel->getStudentById($studentId);
            
            // Lấy danh sách học phần đã chọn
            $selectedCourses = isset($_SESSION['registered_courses']) ? $_SESSION['registered_courses'] : [];
            
            if (empty($selectedCourses)) {
                $_SESSION['error'] = 'Không có học phần nào được chọn để đăng ký!';
                header('Location: index.php?controller=registration&action=register');
                exit;
            }
            
            // Tạo đăng ký mới và lưu vào database
            $registrationId = $this->registrationModel->createRegistration($studentId, $selectedCourses);
            
            if ($registrationId) {
                // Xóa session registered_courses sau khi đăng ký thành công
                unset($_SESSION['registered_courses']);
                
                // Chuyển hướng đến trang thành công
                header('Location: index.php?controller=registration&action=success&id=' . $registrationId);
                exit;
            } else {
                $_SESSION['error'] = 'Đăng ký không thành công. Vui lòng thử lại!';
                header('Location: index.php?controller=registration&action=register');
                exit;
            }
        } else {
            // Hiển thị form xác nhận
            // Lấy thông tin sinh viên
            $studentId = $_SESSION['student_id'];
            $student = $this->studentModel->getStudentById($studentId);
            
            // Lấy danh sách học phần đã chọn
            $selectedCourses = isset($_SESSION['registered_courses']) ? $_SESSION['registered_courses'] : [];
            $registrationDetails = [];
            
            if (empty($selectedCourses)) {
                $_SESSION['error'] = 'Không có học phần nào được chọn để đăng ký!';
                header('Location: index.php?controller=registration&action=register');
                exit;
            }
            
            // Lấy thông tin chi tiết của từng học phần
            foreach ($selectedCourses as $courseId) {
                $course = $this->courseModel->getCourseById($courseId);
                if ($course) {
                    $registrationDetails[] = $course;
                }
            }
            
            include 'views/registration/confirm.php';
        }
    }

    public function success() {
        $registrationId = isset($_GET['id']) ? $_GET['id'] : 0;
        
        if (!$registrationId) {
            header('Location: index.php?controller=course&action=index');
            exit;
        }
        
        // Lấy thông tin đăng ký
        $registration = $this->registrationModel->getRegistrationById($registrationId);
        $registrationDetails = $this->registrationModel->getRegistrationDetails($registrationId);
        
        include 'views/registration/success.php';
    }

    // Thêm phương thức addItem để thêm một khóa học vào session
    public function addItem($courseId = null) {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['student_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để đăng ký học phần!';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
        
        // Lấy ID học phần từ URL nếu không được truyền vào hàm
        if (!$courseId && isset($_GET['id'])) {
            $courseId = $_GET['id'];
        }
        
        // Kiểm tra học phần tồn tại
        $course = $this->courseModel->getCourseById($courseId);
        if (!$course) {
            $_SESSION['error'] = 'Học phần không tồn tại!';
            header('Location: index.php?controller=course&action=index');
            exit;
        }
        
        // Kiểm tra số lượng học phần còn đủ
        if (!$this->courseModel->hasAvailableSlots($courseId)) {
            $_SESSION['error'] = 'Học phần đã hết chỗ!';
            header('Location: index.php?controller=course&action=index');
            exit;
        }
        
        // Khởi tạo mảng registered_courses nếu chưa tồn tại
        if (!isset($_SESSION['registered_courses'])) {
            $_SESSION['registered_courses'] = [];
        }
        
        // Kiểm tra học phần đã được đăng ký chưa
        if (in_array($courseId, $_SESSION['registered_courses'])) {
            $_SESSION['error'] = 'Học phần này đã được đăng ký!';
            header('Location: index.php?controller=registration&action=view');
            exit;
        }
        
        // Thêm học phần vào danh sách đăng ký
        $_SESSION['registered_courses'][] = $courseId;
        
        // KHI THÊM VÀO DANH SÁCH TẠM KHÔNG GIẢM SỐ LƯỢNG
        // BỎ DÒNG DƯỚI ĐÂY NẾU CÓ:
        // $this->courseModel->decrementQuantity($courseId);
        
        $_SESSION['message'] = 'Đã thêm học phần vào danh sách đăng ký!';
        header('Location: index.php?controller=registration&action=view');
        exit;
    }

    // Phương thức để xóa một học phần khỏi danh sách đăng ký
    public function removeItem() {
        $courseId = isset($_GET['id']) ? $_GET['id'] : null;
        
        if (!$courseId) {
            $_SESSION['error'] = 'Không tìm thấy học phần';
            header('Location: index.php?controller=registration&action=register');
            exit;
        }
        
        // Kiểm tra nếu mảng registered_courses tồn tại
        if (isset($_SESSION['registered_courses'])) {
            // Tìm và xóa học phần
            $key = array_search($courseId, $_SESSION['registered_courses']);
            if ($key !== false) {
                unset($_SESSION['registered_courses'][$key]);
                // Sắp xếp lại mảng
                $_SESSION['registered_courses'] = array_values($_SESSION['registered_courses']);
                $_SESSION['message'] = 'Đã xóa học phần khỏi danh sách';
            } else {
                $_SESSION['error'] = 'Không tìm thấy học phần trong danh sách';
            }
        }
        
        header('Location: index.php?controller=registration&action=register');
        exit;
    }

    // Phương thức để xóa tất cả học phần khỏi danh sách đăng ký
    public function removeAll() {
        // Xóa mảng registered_courses
        if (isset($_SESSION['registered_courses'])) {
            $_SESSION['registered_courses'] = [];
            $_SESSION['message'] = 'Đã xóa tất cả học phần khỏi danh sách';
        }
        
        header('Location: index.php?controller=registration&action=register');
        exit;
    }

    // Thêm phương thức này để hiển thị tất cả đăng ký học phần
    public function listAll() {
        // Lấy tất cả đăng ký học phần
        $allRegistrations = $this->registrationModel->getAllRegistrations();
        
        // Tổ chức dữ liệu theo đăng ký và học phần
        $registrationsByMaDK = [];
        $totalCourseCount = 0;
        
        foreach ($allRegistrations as $registration) {
            if (!isset($registrationsByMaDK[$registration->MaDK])) {
                $registrationsByMaDK[$registration->MaDK] = [
                    'info' => [
                        'MaDK' => $registration->MaDK,
                        'NgayDK' => $registration->NgayDK,
                        'MaSV' => $registration->MaSV,
                        'HoTen' => $registration->HoTen
                    ],
                    'courses' => []
                ];
            }
            
            $registrationsByMaDK[$registration->MaDK]['courses'][] = [
                'MaHP' => $registration->MaHP,
                'TenHP' => $registration->TenHP,
                'SoTinChi' => $registration->SoTinChi,
                'SoLuong' => $registration->SoLuong
            ];
            
            $totalCourseCount++;
        }
        
        include 'views/registration/list_all.php';
    }
}
?>
