<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['base64file']) && $_FILES['base64file']['error'] == 0) {
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $tmpName = $_FILES['base64file']['tmp_name'];
        $base64String = trim(file_get_contents($tmpName));

        // Deteksi jenis file (jpg/png/webp/gif)
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
            $imageType = strtolower($type[1]);
            if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                die("Format gambar tidak didukung.");
            }
        } else {
            $imageType = 'png'; // default
        }

        $imageData = base64_decode($base64String);
        if ($imageData === false) {
            die("Base64 tidak valid.");
        }

        $fileName = 'image_' . time() . '.' . $imageType;
        $filePath = $uploadDir . $fileName;

        file_put_contents($filePath, $imageData);

        // Redirect otomatis untuk download
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . basename($filePath) . "\"");
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "Gagal upload file.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Decode Base64 to Image</title>
</head>
<body>
    <h2>Upload File TXT Berisi Base64</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="base64file" accept=".txt" required>
        <br><br>
        <button type="submit">Upload & Decode</button>
    </form>
</body>
</html>
