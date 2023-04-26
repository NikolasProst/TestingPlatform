<?php
    include('config.php');

    $sql = "SELECT * FROM specializations";
    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['id'];
        $name = $row['name'];
        echo "<option value='$id'>$name</option>";
    }
?>