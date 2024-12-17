<?php
require "dbhimage.inc.php";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $imagename = $_FILES['image']['name'];
    $tmpname = $_FILES['image']['tmp_name'];
    $imgexplode = explode('.', $imagename);
    $imgex = strtolower(end($imgexplode));
    $extensions = ['jpeg', 'jpg', 'png'];
    $maxFileSize = 2 * 1024 * 1024; // 2 MB size limit

    // Check if the file extension is valid
    if (in_array($imgex, $extensions)) {
        // Check if the file size is within the limit
        if ($_FILES['image']['size'] <= $maxFileSize) {
            // Sanitize the image name to prevent issues with special characters
            $newimgname = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $imagename);
            $uploadPath = "images/" . $newimgname;

            // Create the directory if it doesn't exist
            if (!file_exists("images/")) {
                mkdir("images/", 0777, true); // Use recursive creation with appropriate permissions
            }

            // Attempt to move the uploaded file
            if (move_uploaded_file($tmpname, $uploadPath)) {
                // Insert image name into database
                $query = "INSERT INTO imagetry (image) VALUES (:image)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':image', $newimgname);
                $stmt->execute();
                echo "Image uploaded successfully.";
            } else {
                echo "Failed to move uploaded file.";
            }
        } else {
            echo "File size exceeds the 2MB limit.";
        }
    } else {
        echo "Invalid file type. Only JPEG, JPG, and PNG files are allowed.";
    }
} else {
    echo "No file uploaded.";
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="image" accept=".jpg, .jpeg, .png ">
        <button type="submit">SUBMIT</button>
    </form>
</body>

</html>