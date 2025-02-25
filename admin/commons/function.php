<?php

// Kết nối CSDL qua PDO
function connectDB()
{
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $conn;
    } catch (PDOException $e) {
        echo ("Connection failed: " . $e->getMessage());
    }
}

function uploadFile($file, $folderUpload): string|null
{
    // Chuẩn hóa tên tệp để tránh các ký tự đặc biệt
    $fileName = preg_replace(pattern: '/[^A-Za-z0-9.\-_]/', replacement: '_', subject: $file['name']);
    $pathStorage = $folderUpload . time() . '_' . $fileName;
    $to = PATH_ROOT . $pathStorage;

    // Kiểm tra và tạo thư mục nếu chưa tồn tại
    if (!is_dir(filename: PATH_ROOT . $folderUpload) && !mkdir(directory: PATH_ROOT . $folderUpload, permissions: 0777, recursive: true)) {
        echo "Không thể tạo thư mục tải lên: " . PATH_ROOT . $folderUpload;
        return null;
    }

    // Kiểm tra lỗi tải lên tệp và di chuyển tệp đến thư mục đích
    if ($file['error'] === UPLOAD_ERR_OK && move_uploaded_file(from: $file['tmp_name'], to: $to)) {
        return $pathStorage; // Trả về đường dẫn để lưu vào DB
    }

    // Thông báo lỗi nếu tải lên không thành công
    echo "Lỗi tải lên file: " . ($file['error'] ?? 'Không thể di chuyển tệp');
    return null;
}

function deleteFile($file)
{
    $path = PATH_ROOT . $file;
    if (file_exists($path)) {
        if (unlink($path)) {
            echo "Xóa tệp thành công.";
        } else {
            echo "Xóa tệp thất bại.";
        }
    } else {
        echo "Tệp không tồn tại.";
    }
}
