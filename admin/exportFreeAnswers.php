<?php

include('../config.php');

// Set the HTTP headers to force the browser to download the file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="free_answers.csv"');

// Open a stream to write the CSV data
$fp = fopen('php://output', 'w');

// Write the column headers to the CSV file
fputcsv($fp, ['№', 'ID_Вопроса', 'Вопрос', 'Эталонный ответ', 'Ответ пользователя', 'Правильный']);

// Fetch the data from the `ofreeanswersstats` table
$sql = "SELECT * FROM freeanswersstats";
$conn->set_charset("utf8");
$result = $conn->query($sql);

// Loop through the results and write each row to the CSV file
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($fp, [
            $row['id'],
            $row['id_question'],
            $row['text'],
            $row['writeAnswer'],
            $row['userAnswer'],
            ($row['is_true'] == '1') ? 'Да' : 'Нет'
        ]);
    }
}

// Close the file stream
fclose($fp);

?>
