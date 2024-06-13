<?php
include 'connection.php';

if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    $totalFiles = count($_FILES['fileImg']['name']);
    $filesArray = array();

    for ($i = 0; $i < $totalFiles; $i++) {
        $imageName = $_FILES["fileImg"]["name"][$i];
        $tmpName = $_FILES["fileImg"]["tmp_name"][$i];

        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $newImageName = uniqid() . '.' . strtolower($imageExtension);

        move_uploaded_file($tmpName, 'images/' . $newImageName);
        $filesArray[] = $newImageName;
    }

    $filesArray = json_encode($filesArray);
    $stmt = $connection->prepare("INSERT INTO tb_images (name, image) VALUES (:name, :images)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':images', $filesArray);
    $stmt->execute();

    echo "
    <script>
        alert('Successfully Added');
        document.location.href = 'index.php';
    </script>
    ";
}
?>
<html>
<head> </head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        Name:
        <input type="text" name="name" required> <br>
        Image:
        <input type="file" name="fileImg[]" accept=".jpg, .jpeg, .png" required multiple> <br>
        <button type="submit" name="submit">Submit</button>
    </form>
    <br>
    <a href="index.php">Index</a>
</body>
</html>
