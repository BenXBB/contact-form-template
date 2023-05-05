$(".alert-success").hide();

//---------------------- Preloader ----------------------
$(window).on('load', function () {
	if ($('#preloader').length) {
		$("#errorHandlerBox").hide();
		$("#successHandlerBox").hide();
		$('#preloader').delay(1000).fadeOut('slow', function () {
			$(this).remove();
		});
	}
});

// ---------------------- Submitting the form function ----------------------
$("#contactForm").submit(function(e) {
    e.preventDefault();

    // Sending data to validation function. If false, display error, if true, continue with AJAX
    var testValidation = validateForm();
    if (!testValidation) { return };
    
    $.ajax({
        type: 'POST',
        url: 'submitForm.php',
        dataType: 'json',
        data: {
            fname: $("#fname").val(),
            lname: $("#lname").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            message: $("#message").val()
        },

        success: function(result) {
            // If data sent successfully, reset the form
            $("#contactForm")[0].reset();
            $('.status').html("Form submitted!");
            console.log("Form submitted");
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
        }

    }); // End of submitForm btn
}); // End of .submit function
    


// ---------------------- Front end form validation for form submit ----------------------
function validateForm() {
    // Testing first and last name
    var fname =  $("#fname").val();
    var lname =  $("#lname").val();
    if (fname == "" || lname == "") {
        $('.status').html("Name cannot be empty");
        return false;
    }
    // Testing email with empty field and regax
    var email = $("#email").val();
    if (email == "") {
        $('.status').html("Email cannot be empty");
        return false;
    } else {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!re.test(email)){
            $('.status').html("Email format invalid");
            return false;
        }
    }
    // Testing phone number
    var phone =  $("#phone").val();
    if (phone == "") {
        $('.status').html("Phone cannot be empty");
        return false;
    }
    // Testing message
    var message =  $("#message").val();
    if (message == "") {
        $('.status').html("Message cannot be empty");
        return false;
    }

    // If all fields pass, send to AJAX function and show sending prompt
    $('.status').html("Sending...");
    $(".alert-success").fadeIn()
    return true;
  } // end of validateForm



// ---------------------- Getting all contacts function ----------------------
$("#showContacts").click(function() {
    
    $.ajax({
        type: 'GET',
        url: 'getAll.php',
        dataType: 'json',

        success:

        function(result) {
            // Deleting the table if it already exists
            $("#contacts").remove();

            // Appending data table headers to the contactTable div
            $("#contactTable").append(
                '<table id="contacts" class="table table-striped table-hover">' +
                '<thead class="headRow">' +
                '<tr>' +
                    '<th scope="col" class="fNameCol">Name</th>' +
                    '<th scope="col" class="lNameCol">Surname</th>' +
                    '<th scope="col" class="emailCol">Email</th>' +
                    '<th scope="col" class="phoneCol">Phone</th>' +
                    '<th scope="col" class="messageCol">Message</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody id="contactTableContents">' +
                '</tbody>' +
                '</table>'
            ); // end of appending table headers
            
            // Appending each row from the SQL request to the contactTable Contents tbody element
            $.each(result["data"], function (index, row) {
                $("#contactTableContents").append(
                    '<tr data-id="' + row["id"] + '">' +
						'<td class="fNameCol">' + row["firstName"] + '</td>' +
                        '<td class="lNameCol">' + row["lastName"] + '</td>' +
                        '<td class="emailCol">' + row["email"] + '</td>' +
                        '<td class="phoneCol">' + row["phone"] + '</td>' +
                        '<td class="messageCol">' + row["message"] + '</td>' +
					'</tr>'
                ); // End of appending each row
            }); // End of each function

            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
        }

    }); // End of submitForm btn
}); // End of .submit function