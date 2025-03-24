<?php
class CourseController {
    private $courseModel;
    
    public function __construct() {
        $this->courseModel = new Course();
        // Ensure capacity field exists
        $this->courseModel->addCapacityField();
    }
    
    // Display list of courses
    public function index() {
        // Đảm bảo rằng cột SoLuong tồn tại
        $this->courseModel->addSoLuongColumn();
        
        // Lấy danh sách học phần
        $courses = $this->courseModel->getAllCourses();
        
        include 'views/course/index.php';
    }
}
?>
