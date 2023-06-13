function getXMLHTTP() {
    var xmlhttp = false;
    try {
        xmlhttp = new XMLHttpRequest();
    }
    catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch (e) {
            try {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
            }
            catch (e1) {
                xmlhttp = false;
            }
        }
    }

    return xmlhttp;
}

function getSubjectsAndCompetences(specializationId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("subjectAndCompetence").innerHTML = xhr.responseText;
        }
    };
    xhr.open("GET", "getSubjectsAndCompetences.php?specialization_id=" + specializationId, true);
    xhr.send();
}

function getSelectedValue(selectId) {
    var selectEl = document.getElementById(selectId);
    return selectEl.options[selectEl.selectedIndex].value;
}

function filterCompetence(subjectId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("competence").innerHTML = xhr.responseText;
        }
    };

    var selectedCompetence = getSelectedValue("competence");
    var specialization_id = getSelectedValue("specialization");

    if (subjectId === '0') {
        xhr.open("GET", "getCompetences.php?specialization_id=" + specialization_id, true);
    }
    else {
        xhr.open("GET", "getCompetences.php?subject_id=" + subjectId + "&specialization_id=" + specialization_id, true);
    }

    xhr.onload = function() {
        if (selectedCompetence !== '0')
            document.getElementById("competence").value = selectedCompetence;
    };

    xhr.send();

}

function filterSubject(competenceId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("subject").innerHTML = xhr.responseText;
        }
    };

    var selectedSubject = getSelectedValue("subject");
    var specialization_id = getSelectedValue("specialization");

    if (competenceId === '0') {
        xhr.open("GET", "getSubjects.php?specialization_id=" + specialization_id, true);
    }
    else {
        xhr.open("GET", "getSubjects.php?competence_id=" + competenceId + "&specialization_id=" + specialization_id, true);
    }

    xhr.onload = function() {
        if (selectedSubject !== '0')
            document.getElementById("subject").value = selectedSubject;
    };

    xhr.send();
}

function getSubjects(specializationId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("subject").innerHTML = xhr.responseText;
        }
    };
    xhr.open("GET", "getSubjects.php?competence_id=" + specializationId, true);
    xhr.send();
}

function getCompetences(subjectId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("competence").innerHTML += xhr.responseText;
        }
    };
    xhr.open("GET", "getCompetences.php?subject_id=" + subjectId, true);
    xhr.send();
}

function getSubject(spec_id) {
    var strURL = "getSubjects.php?specialization_id=" + spec_id;
    fillElement("subject", strURL);
}

function getCompetence(subject_id) {
    var strURL = "getCompetences.php?subject_id=" + subject_id;
    fillElement("competence", strURL);
}

function generateTest(spec_id, subject_id, competence_id) {
    var strURL = "index.php?spec_id=" + spec_id + ",subject_id=" + subject_id + ",competence_id=" + competence_id;
    fillElement("testdiv", strURL);
}

function fillElement(element_name, strURL) {
    var req = getXMLHTTP();
    if (req) {
        req.onreadystatechange = function () {
            if (req.readyState == 4) {
                // Только в случае нажатия «ОК»
                if (req.status == 200) {
                    document.getElementById(element_name).innerHTML = req.responseText;
                } else {
                    alert("Problem while using XMLHTTP:n" + req.statusText);
                }
            }
        }
        req.open("GET", strURL, true);
        req.send(null);
    }
}

var checkBoxState = false;
var optionId = 2;

function addQuest(testId) {


}

//Добавить вариант ответа
function addOption() {
    var questionType = document.getElementById('question_type').checked;
    if  (!questionType) {
        if (optionId >= 4) {
            alert('Достигнуто максимальное кол-во вариантов ответа!');
            return false;
        } else {
            var optionDiv = document.getElementById('optionDiv');
            if (optionDiv != null) {
                optionId++;
                var newOption = document.createElement('div');
                newOption.className = 'form-group';
                newOption.id = 'div_option_' + optionId;
                newOption.innerHTML = '<input type="checkbox" class="form-check-input" name="writeOption[]" value="' + optionId + '"/>' +
                    '<label for="option_' + optionId + '">Вариант №' + optionId + '</label>' +
                    ' <input type="text" class="form-control" name="option[]" required"/>';

                optionDiv.appendChild(newOption);
            }
        }
    }
}

//Удалить вариант ответа
function delOption() {
    var questionType = document.getElementById('question_type').checked;
    if  (!questionType) {
        if (optionId === 2) {
            alert('Достигнуто минимальное кол-во вариантов ответа!');
            return false;
        } else {
            var optionDiv = document.getElementById('optionDiv');
            var option = document.getElementById('div_option_' + optionId + '');
            if (optionDiv != null) {
                optionDiv.removeChild(option);
                optionId--;
            }
        }
    }
}

function addQuestionOnTest(testId) {
    var req = getXMLHTTP();
    if (req) {
        req.open("GET", 'addQuestion.php?test_id='+ testId +'', true);
        req.send(null);
    }
}

function updateFreeAnswersState() {
    var is_true = document.getElementById('is_true[]')
    alert(is_true);
}
