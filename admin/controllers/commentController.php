<?php
ob_start();
class commentController
{
    public $commentController;

    public function __construct()
    {
        $this->commentController = new comment();
    }

    public function listComment()
    {
        $listComment = $this->commentController->getComment();
        require_once './views/comments/listComment.php';
    }

    public function deleteComments()
    {
        try {
            $binh_luan_id = $_GET['binh_luan_id'];
            if ($this->commentController->deleteComments($binh_luan_id)) {
                echo "<script>
                    alert('Xóa bình luận thành công!');
                    window.location.href='?act=listComments';
                </script>";
            } else {
                echo "<script>
                    alert('Xóa bình luận thất bại!');
                    window.location.href='?act=listComments';
                </script>";
            }
        } catch (Exception $e) {
            echo "<script>
                alert('Xóa thất bại: " . $e->getMessage() . "');
                window.location.href='?act=listComments';
            </script>";
        }
    }
}
