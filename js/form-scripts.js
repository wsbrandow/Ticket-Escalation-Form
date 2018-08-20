$("#contactForm").validator().on("submit", function (event){
    if (event.isDefaultPrevented()) {
        formError();
        submitMSG(false, "Form fields must be filled out completely.")
    } else {
        event.preventDefault();
        submitForm();
        formSuccess(); // If "success" section in the submitForm() fuction is working, then remove this line
    }
});

function submitForm(){
    
    var form = $("#contactForm")[0];
    var formData = new FormData(form);

    $.ajax({
        type: "POST",
        url: "contact.php",
        data: formData, 
        contentType : false,
        processData : false,
        cache: false,
        // Note: Working on debug for next 8 lines. 
        success : function(text){  
            if (text == "success"){
                formSuccess();
            } else {
                formError();
                submitMSG(false,text);
            }
        }
    });
}
function formSuccess(){
    $("#contactForm")[0].reset();
    submitMSG(true, "NOC Escalation Email Submitted Successfully!")
}

function submitMSG(valid, msg){
    if(valid){
        var msgClasses = "h3 text-center tada animated text-success";
    } else {
        var msgClasses = "h3 text-center text-danger";
    }
    $("#msgSubmit").removeClass().addClass(msgClasses).text(msg);
}

function formError(){
    $("#contactForm").removeClass().addClass('').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
        $(this).removeClass();
    });
}

