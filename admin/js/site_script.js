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

function getSubject(spec_id) {
    var strURL = "subjects.php?specialization_id=" + spec_id;
    fillElement("subjectdiv", strURL);
}

function getCompetence(subject_id) {
    var strURL = "competences.php?subject_id=" + subject_id;
    fillElement("competencediv", strURL);
}

function generateTest(spec_id, subject_id, competence_id) {
    var strURL = "test_list.php?spec_id=" + spec_id + ",subject_id=" + subject_id + ",competence_id=" + competence_id;
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

//Удалить вариант ответа
function delOption() {
    if (optionId === 2) {
        alert('Достигнуто минимальное кол-во вариантов ответа!');
        return false;
    } else {
        var optionDiv = document.getElementById('optionDiv');
        var option = document.getElementById('div_option_' + optionId +'');
        if (optionDiv != null) {
            optionDiv.removeChild(option);
            optionId--;
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
