<?php
    include('config.php');

    $competence_id = $_GET['competence_id'];
    $specialization_id = $_GET['specialization_id'];

    if ($competence_id == 0) {
        $sql = "SELECT s.id subjectId, s.name subjectName FROM subjects s
                        left join specialization_to_subjects sts on s.id = sts.id_subject
                        where sts.id_specialization = '$specialization_id'";
    }
    else {
        $sql = "SELECT s.id subjectId, s.name subjectName FROM subjects s
                    left join subjects_to_competence stc on s.id = stc.id_subject
                    left join specialization_to_subjects sts on s.id = sts.id_subject
                    where id_competence = '$competence_id' AND id_specialization = '$specialization_id'";
    }
    echo $sql;
    echo "<option value='0'>Выберите предмет</option>";
    $result = $conn->query($sql);
    while ($row = mysqli_fetch_assoc($result))
    {
        $id = $row['subjectId'];
        $name = $row['subjectName'];
        echo "<option value='$id'>$name</option>";
    }
?>