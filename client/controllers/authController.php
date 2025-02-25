<?php
class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if (isset($_POST['submit'])) {
            $data = [
                'ho_va_ten' => $_POST['name'],
                'email' => $_POST['email'],
                'mat_khau' => $_POST['pass'],
                'dia_chi' => $_POST['address'], 
                'so_dien_thoai' => $_POST['phonenumber'],
                'hinh' => '../Upload/User/nam.jpg'
            ];

            $success = $this->userModel->create($data);
            
            if ($success) {
                $thongbao = "Đăng ký thành công. Vui lòng đăng nhập!!";
                echo "<script>
                    alert('$thongbao');
                    setTimeout(function() {
                        window.location.href = './?act=login';
                    }, 1000);
                  </script>";
            }
        }
        include "./views/register.php";
    }

    public function login() {
        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $pass = $_POST['pass'];
            
            $user = $this->userModel->login($email, $pass);
            
            if ($user) {
                $_SESSION['email'] = $user;
                $thongbao1 = "Đăng nhập thành công!!";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 900)
                </script>";
            } else {
                $thongbao2 = "Sai email hoặc mật khẩu!!";
                echo "<script>alert('$thongbao2')</script>";
            }
        }
        include "./views/login.php";
    }
    public function logout() {
        unset($_SESSION['email']);
        header('Location: index.php');
        exit();
    }

    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $this->userModel->getPasswordByEmail($email);
            
            if ($password) {
                include "./views/forgot-password.php";
            } else {
                $error = "Email does not exist in the system!";
                include "./views/forgot-password.php";
            }
        } else {
            include "./views/forgot-password.php";
        }
    }

    public function updateProfile() {
        if (!isset($_SESSION['email'])) {
            header('Location: ?act=login');
            exit;
        }

        if (isset($_POST['update'])) {
            $data = [
                'tai_khoan_id' => $_SESSION['email']['tai_khoan_id'],
                'ho_va_ten' => $_POST['ho_va_ten'],
                'so_dien_thoai' => $_POST['so_dien_thoai'],
                'dia_chi' => $_POST['dia_chi']
            ];

            // Giữ lại ảnh cũ nếu không upload ảnh mới
            if (!empty($_FILES['hinh']['name'])) {
                $hinh = uploadFile($_FILES['hinh'], '../Upload/User/');
                if ($hinh) {
                    $data['hinh'] = $hinh;
                    // Xóa ảnh cũ nếu không phải ảnh mặc định
                    if ($_POST['hinh_cu'] != '../Upload/User/nam.jpg') {
                        deleteFile($_POST['hinh_cu']);
                    }
                }
            } else {
                $data['hinh'] = $_POST['hinh_cu'];
            }

            // Xử lý mật khẩu nếu có thay đổi
            if (!empty($_POST['mat_khau'])) {
                $data['mat_khau'] = $_POST['mat_khau'];
            }

            if ($this->userModel->update($data)) {
                // Cập nhật lại session
                $_SESSION['email'] = $this->userModel->getUserById($data['tai_khoan_id']);
                $_SESSION['success'] = "Cập nhật thông tin thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật thông tin!";
            }
        }
        
        header('Location: ?act=profile');
        exit;
    }

    public function deleteAccount() {
        if (!isset($_SESSION['email'])) {
            header('Location: ?act=login');
            exit;
        }

        $email = $_SESSION['email']['email'];
        if ($this->userModel->deleteByEmail($email)) {
            unset($_SESSION['email']);
            $_SESSION['success_message'] = "Tài khoản đã được xóa thành công!";
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa tài khoản!";
        }
    }
} 