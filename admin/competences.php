<?php

    include('../config.php');

	$subject_id = $_GET['subject_id'];

	$sql = "SELECT c.name, c.id FROM subjects s
                LEFT JOIN subjects_to_competence stc ON s.id = stc.id_subject 
                LEFT JOIN competences c ON c.id = stc.id_competence 
                    WHERE s.id = '$subject_id';";
    $result = $conn->query($sql);

	while ($row = mysqli_fetch_assoc($result)) 
	{
		$id = $row['id'];
		$name = $row['name'];
		echo "<option value='$id'>$name</option>";
	}
?>