<?php
class Registration {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Get student registrations
    public function getRegistrations($studentId) {
        $this->db->query("SELECT d.MaDK, d.NgayDK, c.MaHP, c.TenHP, c.SoTinChi 
                        FROM DangKy d 
                        JOIN ChiTietDangKy ct ON d.MaDK = ct.MaDK 
                        JOIN HocPhan c ON ct.MaHP = c.MaHP 
                        WHERE d.MaSV = :id 
                        ORDER BY d.NgayDK DESC");
        $this->db->bind(':id', $studentId);
        return $this->db->resultSet();
    }
    
    // Create new registration
    public function createRegistration($studentId, $courses) {
        // Start transaction
        $this->db->query("START TRANSACTION");
        $this->db->execute();
        
        try {
            // Insert into DangKy
            $this->db->query("INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), :masv)");
            $this->db->bind(':masv', $studentId);
            $this->db->execute();
            
            // Get the registration ID
            $registrationId = $this->db->lastInsertId();
            
            // Insert into ChiTietDangKy for each course
            foreach($courses as $courseId) {
                $this->db->query("INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (:madk, :mahp)");
                $this->db->bind(':madk', $registrationId);
                $this->db->bind(':mahp', $courseId);
                $this->db->execute();
                
                // Update course capacity - chỉ nên giảm số lượng ở đây
                $course = $this->getCourseCapacity($courseId);
                if($course && $course->SoLuong > 0) {
                    $newCapacity = $course->SoLuong - 1;
                    $this->updateCourseCapacity($courseId, $newCapacity);
                }
            }
            
            // Commit transaction
            $this->db->query("COMMIT");
            $this->db->execute();
            
            return $registrationId;
        } catch(Exception $e) {
            // Rollback in case of error
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return false;
        }
    }
    
    // Delete registration
    public function deleteRegistration($registrationId) {
        // Start transaction
        $this->db->query("START TRANSACTION");
        $this->db->execute();
        
        try {
            // Get courses in this registration to restore capacity
            $this->db->query("SELECT MaHP FROM ChiTietDangKy WHERE MaDK = :id");
            $this->db->bind(':id', $registrationId);
            $courses = $this->db->resultSet();
            
            // Restore capacity for each course
            foreach($courses as $course) {
                $currentCourse = $this->getCourseCapacity($course->MaHP);
                if($currentCourse) {
                    $newCapacity = $currentCourse->SoLuong + 1;
                    $this->updateCourseCapacity($course->MaHP, $newCapacity);
                }
            }
            
            // Delete from ChiTietDangKy
            $this->db->query("DELETE FROM ChiTietDangKy WHERE MaDK = :id");
            $this->db->bind(':id', $registrationId);
            $this->db->execute();
            
            // Delete from DangKy
            $this->db->query("DELETE FROM DangKy WHERE MaDK = :id");
            $this->db->bind(':id', $registrationId);
            $this->db->execute();
            
            // Commit transaction
            $this->db->query("COMMIT");
            $this->db->execute();
            
            return true;
        } catch(Exception $e) {
            // Rollback in case of error
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return false;
        }
    }
    
    // Get course capacity
    private function getCourseCapacity($courseId) {
        $this->db->query("SELECT SoLuong FROM HocPhan WHERE MaHP = :id");
        $this->db->bind(':id', $courseId);
        return $this->db->single();
    }
    
    // Update course capacity
    private function updateCourseCapacity($courseId, $newCapacity) {
        $this->db->query("UPDATE HocPhan SET SoLuong = :capacity WHERE MaHP = :id");
        $this->db->bind(':capacity', $newCapacity);
        $this->db->bind(':id', $courseId);
        return $this->db->execute();
    }
    
    // Lấy thông tin đăng ký theo ID
    public function getRegistrationById($id) {
        $this->db->query("SELECT * FROM DangKy WHERE MaDK = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Lấy chi tiết đăng ký
    public function getRegistrationDetails($registrationId) {
        $this->db->query("
            SELECT h.*, c.MaDK 
            FROM HocPhan h
            JOIN ChiTietDangKy c ON h.MaHP = c.MaHP
            WHERE c.MaDK = :id
        ");
        $this->db->bind(':id', $registrationId);
        return $this->db->resultSet();
    }
}
?>
