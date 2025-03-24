<?php
class AuthController {
    private $studentModel;
    
    public function __construct() {
        $this->studentModel = new Student();
    }
    
    // Display login form
    public function login() {
        include 'views/auth/login.php';
    }
    
    // Process login
    public function authenticate() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $studentId = trim($_POST['MaSV']);
            
            // Find student by ID
            $student = $this->studentModel->getStudentById($studentId);
            
            if($student) {
                // Student found, set session
                $_SESSION['student_id'] = $student->MaSV;
                $_SESSION['student_name'] = $student->HoTen;
                
                header('Location: index.php?controller=student&action=index');
            } else {
                // Student not found
                $_SESSION['login_error'] = 'Mã sinh viên không tồn tại.';
                header('Location: index.php?controller=auth&action=login');
            }
        } else {
            header('Location: index.php?controller=auth&action=login');
        }
    }
    
    // Logout
    public function logout() {
        // Unset all session variables
        $_SESSION = array();
        
        // Destroy the session
        session_destroy();
        
        header('Location: index.php?controller=auth&action=login');
    }
}
?>
