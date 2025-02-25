<?php
ob_start(); 
class UserController {
    private $userModel;
    public function __construct() {
        $this->userModel = new User();
    }
    public function home() {
        include './views/layout/main.php';
    }
    public function listUser() {    
        $users = $this->userModel->getAll(); 
        include  './views/users/listUser.php';
    }
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imagePath = '../Upload/User/default.jpg';
            if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === 0) {
                $uploadDir = '../Upload/User/';
                $fileName = time() . '_' . basename($_FILES['hinh']['name']);
                $uploadFile = $uploadDir . $fileName;            
                if (move_uploaded_file($_FILES['hinh']['tmp_name'], $uploadFile)) {
                    $imagePath = $uploadFile;
                } else {
                    $_SESSION['error'] = "Không thể tải lên ảnh. Vui lòng thử lại!";
                    include './views/users/createUser.php';
                    return;
                }
            }
            $data = [
                'ho_va_ten' => $_POST['ho_va_ten'],
                'email' => $_POST['email'],
                'mat_khau' => $_POST['mat_khau'],
                'dia_chi' => $_POST['dia_chi'],
                'so_dien_thoai' => $_POST['so_dien_thoai'],
                'vai_tro' => $_POST['vai_tro'],
                'hinh' => $imagePath
            ];
            // Gọi phương thức create trong model để thêm người dùng vào cơ sở dữ liệu
            $success = $this->userModel->create($data);
            if ($success) {
                header("Location: index.php?act=listUser");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi thêm!";
                include './views/users/createUser.php';
            }
        }
        include './views/users/createUser.php';
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $imagePath = $_POST['hinh_cu'];
            
            if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === 0) {
                $uploadDir = '../Upload/User/';
                $fileName = time() . '_' . basename($_FILES['hinh']['name']);
                $uploadFile = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['hinh']['tmp_name'], $uploadFile)) {
                    if ($imagePath != '../Upload/User/default.jpg' && file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $imagePath = $uploadFile;
                }
            }
            $data = [
                'ho_va_ten' => $_POST['ho_va_ten'],
                'email' => $_POST['email'],
                'dia_chi' => $_POST['dia_chi'],
                'so_dien_thoai' => $_POST['so_dien_thoai'],
                'vai_tro' => $_POST['vai_tro'],
                'trang_thai' => isset($_POST['trang_thai']) ? $_POST['trang_thai'] : 1,
                'hinh' => $imagePath
            ];
            $success = $this->userModel->update($id, $data);
            if ($success) {
                header("Location: index.php?act=listUser");
                exit();
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật tài khoản!";
                $user = $this->userModel->getById($id); 
                include './views/users/updateUser.php'; 
            }
        } else {
            $user = $this->userModel->getById($id);
            include './views/users/updateUser.php';
        }
    }
    public function delete($id) {
        // Check if user has orders first
        if ($this->userModel->checkUserHasOrders($id)) {
            $_SESSION['error'] = "Không thể xóa tài khoản này vì người dùng có đơn hàng!";
        } else {
            $success = $this->userModel->delete($id);
            if ($success) {
                $_SESSION['success'] = "Xóa tài khoản thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi xóa tài khoản!";
            }
        }
        header("Location: index.php?act=listUser");
        exit();
    }
   // Hàm uploadFile
function uploadFile($file, $folderUpload) {
    $pathStorage = $folderUpload . time() . $file['name'];
    $from = $file['tmp_name'];
    $to = PATH_ROOT . $pathStorage; // Đường dẫn tuyệt đối
    if (move_uploaded_file($from, $to)) {
        return $pathStorage; // Trả đường dẫn lưu vào DB
    } else {
        return null;
    }
}
    // hàm xử lý xóa file
    function deleteFile($file){
        $path=PATH_ROOT . $file;
        if(file_exists($path)){
            unlink($path);
        }
    }
    public function viewDetail($id) {
        $user = $this->userModel->getById($id);
        
        // Get user's orders if needed
        $orderModel = new OrderModel();
        $userOrders = $orderModel->getOrdersByUserId($id);
        
        include './views/users/userDetail.php';
    }
}
ob_end_flush();
?>
