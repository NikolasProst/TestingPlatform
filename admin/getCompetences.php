<?php
    include('../config.php');

    $subject_id = $_GET['subject_id'];
    $specialization_id = $_GET['specialization_id'];

    if ($subject_id == 0) {
        $sql = "SELECT c.id competenceId, c.name competenceName FROM competences c
                left join subjects_to_competence stc on c.id = stc.id_competence";
    } else {
        $sql = "SELECT c.id competenceId, c.name competenceName FROM competences c
                left join subjects_to_competence stc on c.id = stc.id_competence 
                left join specialization_to_subjects sts on stc.id_subject = sts.id_subject
                where stc.id_subject = '$subject_id' AND  id_specialization = '$specialization_id'";
    }

    echo $sql;
    echo "<option value='0'>Выберите компетенцию</option>";
    $result = $conn->query($sql);
    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['competenceId'];
        $name = $row['competenceName'];
        echo "<option value='$id'>$name</option>";
    }
?>