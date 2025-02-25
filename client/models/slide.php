<?php
function getSlides() {
    $conn = pdo_get_connection();
    $stmt = $conn->prepare("SELECT * FROM slides WHERE trang_thai = 1");
    $stmt->execute();
    return $stmt->fetchAll();
} 