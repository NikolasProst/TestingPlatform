$(document).ready(function() {
    $("#addForm").validate({
        rules: {
            "writeOption[]": {
                required: true,
                minlength: 1
            },

            "question": {
                required: true,
                maxlenght: 10
            }
        },
        messages: {
            "writeOption[]": "Please select at least two types of spam."
        }
    });
});