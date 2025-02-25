<?php
function getBinhLuanBySanPhamId($san_pham_id) {
    $sql = "SELECT bl.*, tk.ho_va_ten, tk.hinh 
            FROM binh_luan bl
            JOIN tai_khoan tk ON bl.tai_khoan_id = tk.tai_khoan_id
            WHERE bl.san_pham_id = ?
            ORDER BY bl.ngay_binh_luan DESC";
    return pdo_query($sql, $san_pham_id);
}

function themBinhLuan($san_pham_id, $tai_khoan_id, $noi_dung) {
    $sql = "INSERT INTO binh_luan(san_pham_id, tai_khoan_id, noi_dung) 
            VALUES (?, ?, ?)";
    pdo_execute($sql, $san_pham_id, $tai_khoan_id, $noi_dung);
}

function xoaBinhLuan($binh_luan_id) {
    $sql = "DELETE FROM binh_luan WHERE binh_luan_id = ?";
    pdo_execute($sql, $binh_luan_id);
} 