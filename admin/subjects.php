<?php

    include('../config.php');

	$specialization_id = $_GET['specialization_id'];

	$sql = "SELECT sb.name, sb.id FROM specializations s 
		LEFT JOIN specialization_to_subjects sts ON s.id = sts.id_specialization 
		LEFT JOIN subjects sb ON sb.id = sts.id_subject 
			WHERE s.id = '$specialization_id';";
    $result = $conn->query($sql);

	while ($row = mysqli_fetch_assoc($result)) 
	{
		$id = $row['id'];
		$name = $row['name'];
		echo "<option value='$id'>$name</option>";


        $strURL = "competences.php?subject_id=" . $row['id'];
        echo '<script type="text/javascript">',
            'fillElement("competencediv", '. $strURL .');',
        '</script>'
        ;
	}
?>