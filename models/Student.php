<?php
class Student {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Get all students
    public function getStudents() {
        $this->db->query("SELECT SinhVien.*, NganhHoc.TenNganh 
                        FROM SinhVien 
                        LEFT JOIN NganhHoc ON SinhVien.MaNganh = NganhHoc.MaNganh 
                        ORDER BY MaSV");
        return $this->db->resultSet();
    }
    
    // Get student by ID
    public function getStudentById($id) {
        $this->db->query("SELECT SinhVien.*, NganhHoc.TenNganh 
                        FROM SinhVien 
                        LEFT JOIN NganhHoc ON SinhVien.MaNganh = NganhHoc.MaNganh 
                        WHERE MaSV = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Add student
    public function addStudent($data) {
        $this->db->query("INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                        VALUES (:masv, :hoten, :gioitinh, :ngaysinh, :hinh, :manganh)");
        $this->db->bind(':masv', $data['MaSV']);
        $this->db->bind(':hoten', $data['HoTen']);
        $this->db->bind(':gioitinh', $data['GioiTinh']);
        $this->db->bind(':ngaysinh', $data['NgaySinh']);
        $this->db->bind(':hinh', $data['Hinh']);
        $this->db->bind(':manganh', $data['MaNganh']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Update student
    public function updateStudent($data) {
        $this->db->query("UPDATE SinhVien 
                        SET HoTen = :hoten, 
                            GioiTinh = :gioitinh, 
                            NgaySinh = :ngaysinh, 
                            Hinh = :hinh, 
                            MaNganh = :manganh 
                        WHERE MaSV = :masv");
        $this->db->bind(':masv', $data['MaSV']);
        $this->db->bind(':hoten', $data['HoTen']);
        $this->db->bind(':gioitinh', $data['GioiTinh']);
        $this->db->bind(':ngaysinh', $data['NgaySinh']);
        $this->db->bind(':hinh', $data['Hinh']);
        $this->db->bind(':manganh', $data['MaNganh']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Delete student
    public function deleteStudent($id) {
        // First, check if this student has any course registrations
        $this->db->query("SELECT * FROM DangKy WHERE MaSV = :id");
        $this->db->bind(':id', $id);
        $registrations = $this->db->resultSet();
        
        if(count($registrations) > 0) {
            // Delete their registrations first
            $this->db->query("DELETE FROM ChiTietDangKy WHERE MaDK IN 
                           (SELECT MaDK FROM DangKy WHERE MaSV = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();
            
            $this->db->query("DELETE FROM DangKy WHERE MaSV = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
        }
        
        // Now delete the student
        $this->db->query("DELETE FROM SinhVien WHERE MaSV = :id");
        $this->db->bind(':id', $id);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Get all departments
    public function getDepartments() {
        $this->db->query("SELECT * FROM NganhHoc ORDER BY MaNganh");
        return $this->db->resultSet();
    }
}
?>
