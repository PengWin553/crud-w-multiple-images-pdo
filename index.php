<?php
include 'connection.php';
?>
<html>
<head> </head>
<body>
    <table border=1 cellspacing=0 cellpadding=10>
        <tr>
            <td>#</td>
            <td>Name</td>
            <td>Image</td>
            <td>Action</td>
        </tr>
        <?php
        $stmt = $connection->query("SELECT * FROM tb_images");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = 1;
        foreach ($rows as $row) :
        ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($row["name"]); ?></td>
            <td style="display: flex; align-items: center; gap: 10px;">
                <?php foreach (json_decode($row["image"]) as $image) : ?>
                <img src="images/<?php echo htmlspecialchars($image); ?>" width=100>
                <?php endforeach; ?>
            </td>
            <td>
                <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <a href="upload.php">Upload Image</a>
</body>
</html>
