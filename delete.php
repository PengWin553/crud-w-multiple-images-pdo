<?php
include 'connection.php';

$id = $_GET['id'];

// Fetch the current images to delete them from the server
$stmt = $connection->prepare("SELECT image FROM tb_images WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$currentImages = json_decode($row['image']);

foreach ($currentImages as $image) {
    if (file_exists('images/' . $image)) {
        unlink('images/' . $image);
    }
}

// Delete the record from the database
$stmt = $connection->prepare("DELETE FROM tb_images WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();

echo "
<script>
    alert('Successfully Deleted');
    document.location.href = 'index.php';
</script>
";
?>
