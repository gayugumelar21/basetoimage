<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['base64file']) && $_FILES['base64file']['error'] == 0) {
        $tmpName = $_FILES['base64file']['tmp_name'];
        $base64String = trim(file_get_contents($tmpName));

        // Coba deteksi format gambar dari prefix base64
        $imageType = 'png'; // default
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            $imageType = strtolower($type[1]);
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
        }

        // Decode base64
        $imageData = base64_decode($base64String);
        if ($imageData === false) {
            die("Base64 tidak valid.");
        }

        // Set nama file yang akan di-download
        $fileName = 'image_' . time() . '.' . $imageType;

        // Kirim gambar ke browser langsung untuk didownload
        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Length: " . strlen($imageData));
        echo $imageData;
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
