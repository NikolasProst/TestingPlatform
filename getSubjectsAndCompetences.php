<?php
    include('config.php');

    $specialization_id = $_GET['specialization_id'];

    $sql = "SELECT s.id subjectId, s.name subjectName FROM subjects s
                        left join specialization_to_subjects sts on s.id = sts.id_subject
                        where sts.id_specialization = '$specialization_id'";

    $result = $conn->query($sql);
    $html = '<div class="form-group"><label for="inputState">Предмет</label><select id="subject" class="form-control" name="subject" id="subject" onchange="filterCompetence(this.value)" ><option value="0">Выберите предмет</option>';

    $subjectIds = array();

    while ($row = mysqli_fetch_assoc($result))
    {
        $subjectId = $row['subjectId'];
        $subjectIds[] = $subjectId;
        $subjectName = $row['subjectName'];
        $html .= '<option value='.$subjectId.'>'.$subjectName.'</option>';
    }

    $html .= '</select></div>';
    $html .= '<div class="form-group"><label for="inputState">Компетенция</label><select id="competence" class="form-control" name="competence" id="competence" onchange="filterSubject(this.value)" required><option value="0">Выберите компетенцию</option>';

    foreach ($subjectIds as $subjectId) {
        $sql = "SELECT c.id competenceId, c.name competenceName FROM competences c
                            left join subjects_to_competence stc on c.id = stc.id_competence
                            where id_subject = '$subjectId'";
        $competency_result = $conn->query($sql);

        while ($row1 = mysqli_fetch_assoc($competency_result))
        {
            $competenceId = $row1['competenceId'];
            $competenceName = $row1['competenceName'];
            $html .= '<option value=' . $competenceId . '>' . $competenceName . '</option>';
        }
    }

    $html .= '</select></div>';

    echo $html;
?>