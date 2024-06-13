<?php
include 'connection.php';

$id = $_GET['id'];
$stmt = $connection->prepare("SELECT * FROM tb_images WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$currentImages = json_decode($row['image']);

if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    $totalFiles = count($_FILES['fileImg']['name']);
    $filesArray = $currentImages;

    // Handle the new images
    for ($i = 0; $i < $totalFiles; $i++) {
        if ($_FILES["fileImg"]["name"][$i]) {
            $imageName = $_FILES["fileImg"]["name"][$i];
            $tmpName = $_FILES["fileImg"]["tmp_name"][$i];

            $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
            $newImageName = uniqid() . '.' . strtolower($imageExtension);

            move_uploaded_file($tmpName, 'images/' . $newImageName);
            $filesArray[] = $newImageName;
        }
    }

    // Handle the removal of selected images
    if (!empty($_POST['remove_images'])) {
        foreach ($_POST['remove_images'] as $removeImage) {
            $index = array_search($removeImage, $filesArray);
            if ($index !== false) {
                unset($filesArray[$index]);
                if (file_exists('images/' . $removeImage)) {
                    unlink('images/' . $removeImage);
                }
            }
        }
    }

    $filesArray = json_encode(array_values($filesArray)); // Re-index the array
    $stmt = $connection->prepare("UPDATE tb_images SET name = :name, image = :images WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':images', $filesArray);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    echo "
    <script>
        alert('Successfully Updated');
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
        <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required> <br>
        Current Images:<br>
        <?php foreach ($currentImages as $image): ?>
            <div style="display: inline-block; margin: 10px; position: relative;">
                <img src="images/<?php echo htmlspecialchars($image); ?>" width=100>
                <input type="checkbox" name="remove_images[]" value="<?php echo htmlspecialchars($image); ?>"> Remove
            </div>
        <?php endforeach; ?>
        <br>
        Add New Images:
        <input type="file" name="fileImg[]" accept=".jpg, .jpeg, .png" multiple> <br>
        <button type="submit" name="submit">Update</button>
    </form>
    <br>
    <a href="index.php">Index</a>
</body>
</html>
