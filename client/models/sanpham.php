<?php
function loadall_sanpham_home()
{
    $sql = "SELECT  sp.san_pham_id, sp.ten_san_pham, sp.gia, sp.ngay_nhap, sp.mo_ta, sp.trang_thai, 
            dm.ten_danh_muc, dm.danh_muc_id,
            MIN(hasp.hinh_sp) as hinh_sp
            FROM san_pham sp 
            LEFT JOIN hinh_anh_san_pham hasp ON sp.san_pham_id = hasp.san_pham_id 
            LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.danh_muc_id
            GROUP BY sp.san_pham_id, sp.ten_san_pham, sp.gia, sp.ngay_nhap, sp.mo_ta, 
                     sp.trang_thai, dm.ten_danh_muc, dm.danh_muc_id
            ORDER BY sp.san_pham_id DESC LIMIT 10  ";

    $listsanpham = pdo_query($sql);
    return $listsanpham;
}
function loadall_sanpham_top10()
{
    $sql = "SELECT sp.*, hasp.hinh_sp 
            FROM san_pham sp
            LEFT JOIN hinh_anh_san_pham hasp ON sp.san_pham_id = hasp.san_pham_id
            WHERE sp.trang_thai = 1
            ORDER BY sp.so_luot_xem DESC 
            LIMIT 10";
    return pdo_query($sql);
}
function getProductRams($san_pham_id) {
    $sql = "SELECT r.* 
            FROM ram r
            INNER JOIN san_pham_ram spr ON r.ram_id = spr.ram_id
            WHERE spr.san_pham_id = ?";
            
    return pdo_query($sql, $san_pham_id);
}
function getProductById($id)
{
    increaseProductView($id);
    
    $sql = "SELECT sp.*, dm.ten_danh_muc,
            (SELECT hinh_sp FROM hinh_anh_san_pham WHERE san_pham_id = sp.san_pham_id LIMIT 1) as hinh_sp,
            sp.stock
            FROM san_pham sp
            LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.danh_muc_id
            WHERE sp.san_pham_id = ?";
    $product = pdo_query_one($sql, $id);
    
    if ($product) {
        $product['rams'] = getProductRams($id);
    }
    
    return $product;
}
function increaseProductView($id)
{
    $sql = "UPDATE san_pham SET so_luot_xem = so_luot_xem + 1 WHERE san_pham_id = ?";
    return pdo_execute($sql, $id);
}
function search_products($keyword) {
    $sql = "SELECT sp.san_pham_id, sp.ten_san_pham, sp.gia, sp.mo_ta, sp.trang_thai,
            MIN(hasp.hinh_sp) as hinh_sp
            FROM san_pham sp
            LEFT JOIN hinh_anh_san_pham hasp ON sp.san_pham_id = hasp.san_pham_id
            WHERE sp.trang_thai = 1 
            AND LOWER(sp.ten_san_pham) LIKE LOWER(?)
            GROUP BY sp.san_pham_id, sp.ten_san_pham, sp.gia, sp.mo_ta, sp.trang_thai
            ORDER BY sp.ten_san_pham ASC";
            
    $keyword = "%{$keyword}%";
    return pdo_query($sql, $keyword);
}
function search_products_by_category($category) {
    $sql = "SELECT sp.san_pham_id, sp.ten_san_pham, sp.gia, sp.mo_ta, sp.trang_thai,
            MIN(hasp.hinh_sp) as hinh_sp,
            dm.ten_danh_muc
            FROM san_pham sp
            LEFT JOIN hinh_anh_san_pham hasp ON sp.san_pham_id = hasp.san_pham_id
            LEFT JOIN danh_muc dm ON sp.danh_muc_id = dm.danh_muc_id
            WHERE sp.trang_thai = 1 
            AND LOWER(dm.ten_danh_muc) LIKE LOWER(?)
            GROUP BY sp.san_pham_id, sp.ten_san_pham, sp.gia, sp.mo_ta, sp.trang_thai, dm.ten_danh_muc
            ORDER BY sp.ten_san_pham ASC";
            
    $category = "%{$category}%";
    $products = pdo_query($sql, $category);
    
    // Debug
    if (empty($products)) {
        error_log("Không tìm thấy sản phẩm cho danh mục: " . $category);
        error_log("SQL Query: " . $sql);
    }
    
    return $products;
}
function loadAllCategories() {
    $sql = "SELECT * FROM danh_muc WHERE trang_thai = 1 ORDER BY ten_danh_muc ASC"; // Assuming there's a 'trang_thai' field to filter active categories
    return pdo_query($sql);
}
function decreaseProductStock($san_pham_id, $quantity) {
    $sql = "UPDATE san_pham SET stock = stock - ? WHERE san_pham_id = ?";
    return pdo_execute($sql, $quantity, $san_pham_id);
}
