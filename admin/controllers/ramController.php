<?php  
class RamController {


    public $modelRam;

    public function __construct()
    {
        $this->modelRam = new Ram();
    }

    public function formAddRam(){
        require_once './views/ram/formAddRam.php';
    }

    public function addRam() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dung_luong = $_POST['dung_luong'];
            $gia_tang = $_POST['gia_tang'];
            $trang_thai = $_POST['trang_thai'];

            $existingRam = $this->modelRam->checkRamExists($dung_luong);
            if ($existingRam) {
                echo "<script>
                    alert('Biến thể này đã có rồi!'); 
                    window.location.href='index.php?act=formAddRam';
                </script>";
                return;
            }

            $result = $this->modelRam->addRam($dung_luong, $gia_tang, $trang_thai);

            if ($result) {
                echo "<script>
                    alert('Thêm RAM thành công!'); 
                    window.location.href='index.php?act=listRams';
                </script>";
            } else {
                echo "<script>
                    alert('Thêm RAM thất bại!'); 
                    window.location.href='index.php?act=formAddRam';
                </script>";
            }
        }
    }

    public function listRam(){
        $rams = $this->modelRam->getAllRam();
        
        if (!is_array($rams)) {
            $rams = [];
        }

        require_once './views/ram/listRam.php';
    }

    // public function updateProductRam() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         try {
    //             $san_pham_id = (int)$_POST['san_pham_id'];
    //             $ram_id = (int)$_POST['ram_id'];
    //             $gia_them = (float)$_POST['gia_them'];

    //             $result = $this->modelRam->updateProductRamPrice($san_pham_id, $ram_id, $gia_them);

    //             if ($result) {
    //                 echo json_encode(['success' => true]);
    //             } else {
    //                 echo json_encode(['success' => false]);
    //             }
    //         } catch (Exception $e) {
    //             echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    //         }
    //     }
    // }

    public function formEditRam() {
        if(isset($_GET['id'])) {
            $ram_id = $_GET['id'];
            $ram = $this->modelRam->getRamById($ram_id);
            require_once './views/ram/formEditRam.php';
        }
    }

    public function editRam() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ram_id = $_POST['ram_id'];
            $dung_luong = $_POST['dung_luong'];
            $gia_tang = $_POST['gia_tang'];
            $trang_thai = $_POST['trang_thai'];

            $result = $this->modelRam->updateRam($ram_id, $dung_luong, $gia_tang, $trang_thai);

            if ($result) {
                echo "<script>alert('Cập nhật RAM thành công!'); window.location.href='index.php?act=listRams';</script>";
            } else {
                echo "<script>alert('Cập nhật RAM thất bại!'); window.location.href='index.php?act=formEditRam&id=".$ram_id."';</script>";
            }
        }
    }

    public function deleteRam() {
        if(isset($_GET['id'])) {
            $ram_id = $_GET['id'];
            $result = $this->modelRam->deleteRam($ram_id);
            
            if ($result) {
                echo "<script>alert('Xóa RAM thành công!'); window.location.href='index.php?act=listRams';</script>";
            } else {
                echo "<script>alert('Xóa RAM thất bại!'); window.location.href='index.php?act=listRams';</script>";
            }
        }
    }

}

?>