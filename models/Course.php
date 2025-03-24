<?php
class Course {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Lấy tất cả học phần
    public function getAllCourses() {
        $this->db->query("SELECT * FROM HocPhan ORDER BY MaHP");
        return $this->db->resultSet();
    }
    
    // Lấy học phần theo ID
    public function getCourseById($id) {
        $this->db->query("SELECT * FROM HocPhan WHERE MaHP = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Thêm học phần mới
    public function addCourse($data) {
        $this->db->query("INSERT INTO HocPhan (MaHP, TenHP, SoTinChi, SoLuong) 
                        VALUES (:mahp, :tenhp, :sotinchi, :soluong)");
        $this->db->bind(':mahp', $data['MaHP']);
        $this->db->bind(':tenhp', $data['TenHP']);
        $this->db->bind(':sotinchi', $data['SoTinChi']);
        $this->db->bind(':soluong', $data['SoLuong'] ?? 100);
        
        return $this->db->execute();
    }
    
    // Cập nhật học phần
    public function updateCourse($data) {
        $this->db->query("UPDATE HocPhan SET 
                        TenHP = :tenhp, 
                        SoTinChi = :sotinchi, 
                        SoLuong = :soluong 
                        WHERE MaHP = :mahp");
        $this->db->bind(':mahp', $data['MaHP']);
        $this->db->bind(':tenhp', $data['TenHP']);
        $this->db->bind(':sotinchi', $data['SoTinChi']);
        $this->db->bind(':soluong', $data['SoLuong'] ?? 100);
        
        return $this->db->execute();
    }
    
    // Xóa học phần
    public function deleteCourse($id) {
        // Kiểm tra xem học phần có đang được sử dụng không
        $this->db->query("SELECT COUNT(*) as count FROM ChiTietDangKy WHERE MaHP = :id");
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        
        if ($result->count > 0) {
            return false; // Học phần đang được sử dụng, không thể xóa
        }
        
        // Xóa học phần
        $this->db->query("DELETE FROM HocPhan WHERE MaHP = :id");
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    // Cập nhật số lượng 
    public function updateQuantity($courseId, $quantity) {
        $this->db->query("UPDATE HocPhan SET SoLuong = :quantity WHERE MaHP = :id");
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $courseId);
        return $this->db->execute();
    }
    
    // Kiểm tra nếu cột SoLuong chưa tồn tại
    public function addSoLuongColumn() {
        try {
            // Kiểm tra xem column SoLuong đã tồn tại chưa
            $this->db->query("SHOW COLUMNS FROM HocPhan LIKE 'SoLuong'");
            $result = $this->db->resultSet();
            
            if (empty($result)) {
                // Thêm column SoLuong nếu chưa tồn tại
                $this->db->query("ALTER TABLE HocPhan ADD COLUMN SoLuong INT DEFAULT 100");
                $this->db->execute();
                
                // Cập nhật giá trị mặc định
                $this->db->query("UPDATE HocPhan SET SoLuong = 100");
                $this->db->execute();
            }
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Lấy học phần theo ngành
    public function getCoursesByDepartment($departmentId) {
        $this->db->query("SELECT * FROM HocPhan WHERE MaHP LIKE :pattern ORDER BY MaHP");
        $this->db->bind(':pattern', $departmentId . '%');
        return $this->db->resultSet();
    }
    
    // Kiểm tra học phần có sẵn để đăng ký
    public function isCourseAvailable($courseId) {
        $this->db->query("SELECT SoLuong FROM HocPhan WHERE MaHP = :id");
        $this->db->bind(':id', $courseId);
        $result = $this->db->single();
        
        if (!$result) {
            return false;
        }
        
        return $result->SoLuong > 0;
    }
    
    // Lấy tổng số tín chỉ cho một danh sách học phần
    public function getTotalCredits($courseIds) {
        if (empty($courseIds)) {
            return 0;
        }
        
        // Chuyển đổi mảng courseIds thành format cho IN clause
        $placeholders = implode(',', array_fill(0, count($courseIds), '?'));
        
        $this->db->query("SELECT SUM(SoTinChi) as total FROM HocPhan WHERE MaHP IN ($placeholders)");
        
        // Bind các giá trị
        foreach ($courseIds as $index => $id) {
            $this->db->bind($index + 1, $id);
        }
        
        $result = $this->db->single();
        return $result->total ?? 0;
    }
    
    // Thêm phương thức getCourses()
    public function getCourses() {
        $this->db->query("SELECT * FROM HocPhan ORDER BY MaHP");
        return $this->db->resultSet();
    }

    public function addCapacityField() {
        try {
            // Kiểm tra xem column SoLuong đã tồn tại chưa
            $this->db->query("SHOW COLUMNS FROM HocPhan LIKE 'SoLuong'");
            $result = $this->db->resultSet();
            
            if (empty($result)) {
                // Thêm column SoLuong nếu chưa tồn tại
                $this->db->query("ALTER TABLE HocPhan ADD COLUMN SoLuong INT DEFAULT 100");
                $this->db->execute();
                
                // Cập nhật giá trị mặc định
                $this->db->query("UPDATE HocPhan SET SoLuong = 100");
                $this->db->execute();
            }
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Thêm phương thức addSoLuongColumnIfNotExists
    public function addSoLuongColumnIfNotExists() {
        try {
            $this->db->query("SHOW COLUMNS FROM HocPhan LIKE 'SoLuong'");
            $columnExists = $this->db->resultSet();
            
            if (empty($columnExists)) {
                // Thêm cột SoLuong nếu chưa tồn tại
                $this->db->query("ALTER TABLE HocPhan ADD COLUMN SoLuong INT DEFAULT 100");
                $this->db->execute();
                
                // Cập nhật giá trị mặc định
                $this->db->query("UPDATE HocPhan SET SoLuong = 100");
                $this->db->execute();
                
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    // Thêm phương thức giảm số lượng
    public function decrementQuantity($courseId) {
        $this->db->query("UPDATE HocPhan SET SoLuong = SoLuong - 1 WHERE MaHP = :id AND SoLuong > 0");
        $this->db->bind(':id', $courseId);
        return $this->db->execute();
    }

    // Thêm phương thức kiểm tra đủ số lượng
    public function hasAvailableSlots($courseId) {
        $this->db->query("SELECT SoLuong FROM HocPhan WHERE MaHP = :id");
        $this->db->bind(':id', $courseId);
        $result = $this->db->single();
        
        if (!$result) {
            return false;
        }
        
        return $result->SoLuong > 0;
    }
}
?>
