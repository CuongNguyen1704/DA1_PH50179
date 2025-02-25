<?php
ob_start();
class CategoryController
{
    public $modelCategory;

    public function __construct()
    {
        $this->modelCategory = new Category();
    }

    public function listCategory()
    {
        $listCategory = $this->modelCategory->getCategory();
        require_once './views/category/formListCategory.php';
    }

    public function formAddCategory()
    {
        require_once './views/category/formAddCategory.php';
    }

    public function postAddCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
            $ten_danh_muc = $_POST['ten_danh_muc'];
            $mo_ta = $_POST['mo_ta'];
            $trang_thai = $_POST['trang_thai'];

            $img = uploadFile($_FILES['hinh'], '../Upload/Category/');

            if ($this->modelCategory->addCategory($ten_danh_muc, $img, $mo_ta, $trang_thai)) {
                $_SESSION['message'] = "Thêm danh mục thành công!";
                $_SESSION['message_type'] = "success";
                header('Location: ./?act=list-category');
                exit();
            } else {
                $_SESSION['message'] = "Thêm danh mục thất bại!";
                $_SESSION['message_type'] = "danger";
                header('Location: ./?act=form-add-category');
                exit();
            }
        }
    }

    public function postEditCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
            $danh_muc_id = $_POST['danh_muc_id'];
            $ten_danh_muc = $_POST['ten_danh_muc'];
            $mo_ta = $_POST['mo_ta'];
            $trang_thai = $_POST['trang_thai'];

            if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === UPLOAD_ERR_OK) {
                if (!empty($_POST['old_img'])) {
                    deleteFile($_POST['old_img']);
                }
                $new_img = uploadFile($_FILES['hinh'], '../Upload/Category/');
            } else {
                $new_img = $_POST['old_img'];
            }

            if ($this->modelCategory->updateCategory($danh_muc_id, $ten_danh_muc, $new_img, $mo_ta, $trang_thai)) {
                $_SESSION['message'] = "Cập nhật danh mục thành công!";
                $_SESSION['message_type'] = "success";
                header('Location: ./?act=list-category');
                exit();
            } else {
                $_SESSION['message'] = "Cập nhật danh mục thất bại!";
                $_SESSION['message_type'] = "danger";
                header('Location: ./?act=form-edit-category&danh_muc_id=' . $danh_muc_id);
                exit();
            }
        } else {
            echo "Không có dữ liệu gửi đi!";
        }
    }

    public function formEditCategory()
    {
        $danh_muc_id = $_GET['danh_muc_id'];
        $Category = $this->modelCategory->inforCategory($danh_muc_id);
        require_once './views/category/formEditCategory.php';
    }

    public function postDeleteCategory()
    {
        $danh_muc_id = $_GET['danh_muc_id'];

        // Kiểm tra ràng buộc trước khi xóa
        $constraints = $this->modelCategory->checkCategoryConstraints($danh_muc_id);

        if ($constraints['has_products']) {
            $_SESSION['message'] = "Không thể xóa vì danh mục này đang có sản phẩm!";
            $_SESSION['message_type'] = "danger";
            header('Location: ?act=list-category');
            exit();
        }

        if ($constraints['has_orders']) {
            $_SESSION['message'] = "Không thể xóa vì danh mục này có đơn hàng!";
            $_SESSION['message_type'] = "danger";
            header('Location: ?act=list-category');
            exit();
        }

        if ($this->modelCategory->deleteCategory($danh_muc_id)) {
            $_SESSION['message'] = "Xóa danh mục thành công!";
            $_SESSION['message_type'] = "success";
            header('Location: ?act=list-category');
        } else {
            $_SESSION['message'] = "Xóa danh mục thất bại!";
            $_SESSION['message_type'] = "danger";
            header('Location: ?act=list-category');
        }
        exit();
    }
}
