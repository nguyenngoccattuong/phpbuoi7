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
        // Check if student is logged in
        if(!isset($_SESSION['student_id'])) {
            header('Location: index.php?controller=auth&action=login');
            return;
        }
        
        $courses = $this->courseModel->getCourses();
        include 'views/course/register.php';
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
        // Kiểm tra người dùng đã đăng nhập
        if (!isset($_SESSION['student_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để đăng ký học phần';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Lấy thông tin sinh viên
        $studentId = $_SESSION['student_id'];
        $student = $this->studentModel->getStudentById($studentId);

        // Lấy học phần đã đăng ký từ session
        $registeredCourses = isset($_SESSION['registered_courses']) ? $_SESSION['registered_courses'] : [];

        if (empty($registeredCourses)) {
            $_SESSION['error'] = 'Bạn chưa đăng ký học phần nào';
            header('Location: index.php?controller=course&action=index');
            exit;
        }

        // Lấy chi tiết học phần đã đăng ký
        $registrationDetails = [];
        foreach ($registeredCourses as $courseId) {
            $course = $this->courseModel->getCourseById($courseId);
            if ($course) {
                $registrationDetails[] = $course;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lưu đăng ký vào database
            $registrationId = $this->registrationModel->createRegistration($studentId, $registeredCourses);
            
            if ($registrationId) {
                // Cập nhật số lượng dự kiến cho mỗi học phần
                foreach ($registeredCourses as $courseId) {
                    $course = $this->courseModel->getCourseById($courseId);
                    if ($course && isset($course->SoLuong) && $course->SoLuong > 0) {
                        $newQuantity = $course->SoLuong - 1;
                        $this->courseModel->updateQuantity($courseId, $newQuantity);
                    }
                }

                // Xóa thông tin đăng ký trong session
                unset($_SESSION['registered_courses']);
                
                // Thông báo thành công
                $_SESSION['message'] = 'Đăng ký học phần thành công!';
                
                // Chuyển đến trang thông báo thành công
                header('Location: index.php?controller=registration&action=success&id=' . $registrationId);
                exit;
            } else {
                $_SESSION['error'] = 'Đăng ký học phần thất bại. Vui lòng thử lại.';
                include 'views/registration/confirm.php';
            }
        } else {
            // Hiển thị form xác nhận
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

    // Thêm phương thức addItem()
    public function addItem() {
        // Kiểm tra có id học phần truyền vào không
        if (!isset($_GET['id'])) {
            $_SESSION['error'] = 'Không tìm thấy học phần';
            header('Location: index.php?controller=course&action=index');
            exit;
        }
        
        $courseId = $_GET['id'];
        
        // Kiểm tra học phần có tồn tại không
        $course = $this->courseModel->getCourseById($courseId);
        if (!$course) {
            $_SESSION['error'] = 'Không tìm thấy học phần';
            header('Location: index.php?controller=course&action=index');
            exit;
        }
        
        // Kiểm tra số lượng còn lại
        if (isset($course->SoLuong) && $course->SoLuong <= 0) {
            $_SESSION['error'] = 'Học phần đã hết chỗ';
            header('Location: index.php?controller=course&action=index');
            exit;
        }
        
        // Khởi tạo mảng đăng ký trong session nếu chưa có
        if (!isset($_SESSION['registered_courses'])) {
            $_SESSION['registered_courses'] = [];
        }
        
        // Thêm học phần vào danh sách đăng ký
        if (!in_array($courseId, $_SESSION['registered_courses'])) {
            $_SESSION['registered_courses'][] = $courseId;
            $_SESSION['message'] = 'Đã thêm học phần vào danh sách đăng ký';
        } else {
            $_SESSION['error'] = 'Học phần này đã có trong danh sách đăng ký';
        }
        
        // Chuyển đến trang xem danh sách đăng ký
        header('Location: index.php?controller=registration&action=view');
        exit;
    }

    // Thêm phương thức removeItem()
    public function removeItem() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=registration&action=view');
            exit;
        }
        
        $courseId = $_GET['id'];
        
        if (isset($_SESSION['registered_courses'])) {
            $key = array_search($courseId, $_SESSION['registered_courses']);
            if ($key !== false) {
                unset($_SESSION['registered_courses'][$key]);
                // Reindex array
                $_SESSION['registered_courses'] = array_values($_SESSION['registered_courses']);
                $_SESSION['message'] = 'Đã xóa học phần khỏi danh sách đăng ký';
            }
        }
        
        header('Location: index.php?controller=registration&action=view');
        exit;
    }

    // Thêm phương thức removeAll() 
    public function removeAll() {
        unset($_SESSION['registered_courses']);
        $_SESSION['message'] = 'Đã xóa tất cả học phần khỏi danh sách đăng ký';
        header('Location: index.php?controller=registration&action=view');
        exit;
    }
}
?>
