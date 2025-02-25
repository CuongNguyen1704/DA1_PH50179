<?php

class ProductsController
{
    public $modelProducts;
    private $conn;

    public function __construct()
    {
        $this->modelProducts = new Products();
        $this->conn = connectDB();
    }

    public function getAllRam()
    {
        return $this->modelProducts->getAllRam();
    }

    public function listProducts()
    {
        $listProducts = $this->modelProducts->getAllProducts();
        // var_dump($listProducts);
        echo "<!-- Debug: ";
        // var_dump($listProducts);
        echo " -->";
        
        require_once './views/Products/listProducts.php';
    }

    public function formAddProducts()
    {
        $categories = $this->modelProducts->getCategories();
        $rams = $this->modelProducts->getAllRam();
        require_once './views/Products/formAddProducts.php';
    }


    public function postFormAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) {
            try {
                // Debug dữ liệu POST
                error_log("POST Data: " . print_r($_POST, true));
                error_log("FILES Data: " . print_r($_FILES, true));
                
                $this->conn->beginTransaction();
                $ten_san_pham = $_POST['ten_san_pham'];
                $gia = $_POST['gia'];
                $hinh = $_FILES['hinh_sp'];
                $ngay_nhap = $_POST['ngay_nhap'];
                $mo_ta = $_POST['mo_ta'];
                $trang_thai = $_POST['stock'] > 0 ? 1 : 0;
                $danh_muc_id = isset($_POST['danh_muc']) ? $_POST['danh_muc'] : NULL;
                

                // Debug dữ liệu trước khi thêm - Sửa lại cách sử dụng error_log
                error_log("Data to insert: " . print_r([
                    'ten_san_pham' => $ten_san_pham,
                    'gia' => $gia,
                    'ngay_nhap' => $ngay_nhap,
                    'mo_ta' => $mo_ta,
                    'trang_thai' => $trang_thai,
                    'danh_muc_id' => $danh_muc_id
                ], true));

                // Thêm sản phẩm và lấy ID
                $result = $this->modelProducts->addProduct($ten_san_pham, $gia, $ngay_nhap, $mo_ta, $trang_thai, $danh_muc_id, $_POST['stock']);
                
                if (!$result) {
                    throw new Exception("Không thể thêm sản phẩm");
                }
                
                $san_pham_id = $result['san_pham_id'];

                // Thêm RAM cho sản phẩm
                if (isset($_POST['ram_ids']) && is_array($_POST['ram_ids'])) {
                    $ram_result = $this->modelProducts->updateProductRams($san_pham_id, $_POST['ram_ids']);
                    if (!$ram_result) {
                        throw new Exception("Không thể thêm RAM cho sản phẩm");
                    }
                }

                // Xử lý hình ảnh
                foreach ($hinh['name'] as $key => $value) {
                    if ($hinh['error'][$key] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $hinh['name'][$key],
                            'type' => $hinh['type'][$key],
                            'tmp_name' => $hinh['tmp_name'][$key],
                            'error' => $hinh['error'][$key],
                            'size' => $hinh['size'][$key]
                        ];

                        $url = uploadFile($file, '../Upload/Product/');
                        if (!$this->modelProducts->addProductImage($url, $san_pham_id)) {
                            throw new Exception("Không thể thêm hình ảnh sản phẩm");
                        }
                    }
                }

                $this->conn->commit();
                header("location: ?act=listProducts");
                exit();
            } catch (Exception $e) {
                $this->conn->rollBack();
                error_log("Lỗi: " . $e->getMessage());
                echo "<script>alert('Có lỗi xảy ra khi thêm sản phẩm!'); window.history.back();</script>";
            }
        }
    }

    public function formEditProducts()
    {
        if (!isset($_GET['id'])) {
            die("Thiếu tham số ID");
        }

        $id = (int)$_GET['id'];
        if ($id <= 0) {
            die("ID không hợp lệ");
        }

        $categories = $this->modelProducts->getCategories();
        $rams = $this->modelProducts->getAllRam();
        $productRams = $this->modelProducts->getProductRams($id);
        $product = $this->modelProducts->thongTinProduct($id);

        // Debug dữ liệu sản phẩm
        error_log("Product data in controller: " . print_r($product, true));

        if (!$product) {
            die("Không tìm thấy sản phẩm với ID: " . $id);
        }

        require_once './views/Products/editProducts.php';
    }


    
    public function updateProduct() {
        try {
            $san_pham_id = (int)$_POST['san_pham_id'];
            $ten_san_pham = $_POST['ten_san_pham'];
            $gia = (int)$_POST['gia'];
            $ngay_nhap = $_POST['ngay_nhap'];
            $mo_ta = $_POST['mo_ta'];
            $old_img = $_POST['old_img'];
            $trang_thai = (int)$_POST['trang_thai'];
            $danh_muc_id = (int)$_POST['danh_muc_id'];
            $hinh_id = $_POST['hinh_anh_id'];

            // Xử lý hình ảnh
            if(isset($_FILES['hinh_sp']) && $_FILES['hinh_sp']['error'] === UPLOAD_ERR_OK) {
                $hinh_path = uploadFile($_FILES['hinh_sp'], '../Upload/Product/');
            } else {
                $hinh_path = $old_img;
            }

            // Bắt đầu transaction
            $this->conn->beginTransaction();

            // Cập nhật thông tin cơ bản của sản phẩm
            $result = $this->modelProducts->updateProductWithRelations(
                $san_pham_id,
                $ten_san_pham,
                $gia,
                $hinh_id,
                $hinh_path,
                $ngay_nhap,
                $mo_ta,
                $trang_thai,
                $danh_muc_id,
                $_POST['stock']
            );

            // Cập nhật RAM của sản phẩm
            if (isset($_POST['ram_ids']) && is_array($_POST['ram_ids'])) {
                $this->modelProducts->updateProductRams($san_pham_id, $_POST['ram_ids']);
            }

            $this->conn->commit();

            if ($result) {
                echo "<script>alert('Cập nhật thành công'); window.location.href='index.php?act=listProducts';</script>";
            } else {
                echo "<script>alert('Cập nhật thất bại'); window.location.href='index.php?act=listProducts';</script>";
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log($e->getMessage());
            echo "<script>alert('Có lỗi xảy ra!'); window.location.href='index.php?act=formEditProducts&id=".$san_pham_id."';</script>";
        }
    }

    public function deleteProduct($id)
    {
        if (empty($id) || !is_numeric($id)) {
            throw new \Exception('ID không hợp lệ');
        }

        // Kiểm tra sản phẩm có trong đơn hàng không
        $orderCheck = $this->modelProducts->checkProductInOrders($id);
        if ($orderCheck) {
            $_SESSION['error'] = '<strong>Không thể xóa sản phẩm này vì người dùng có đơn hàng!</strong>';
            header('Location: ./?act=listProducts');
            exit();
        }

        // Nếu không có trong đơn hàng thì xóa
        $result = $this->modelProducts->deleteProductAndImages($id);
        if ($result) {
            $_SESSION['success'] = "Xóa sản phẩm thành công!";
        } else {
            $_SESSION['error'] = "Không thể xóa sản phẩm!";
        }
        header('Location: ./?act=listProducts');
        exit();
    }
}