(function($) { 
    $(function() { 
        
        $('nav ul li a:not(:only-child)').click(function(e) {
            $(this).siblings('.nav-dropdown').toggle();
            
            $('.nav-dropdown').not($(this).siblings()).hide();
            e.stopPropagation();
        });
        
        $('html').click(function() {
            $('.nav-dropdown').hide();
        });
        
        $('#nav-toggle').click(function() {
            $('nav ul').slideToggle();
        });

        $('#nav-toggle').on('click', function() {
            this.classList.toggle('active');
        });
    });

})

(jQuery);

jQuery(document).ready(function(jQ) {
    jQ('enquiry_form').submit(function(event) {
        var recaptcha = jQ("#g-recaptcha").val();
        if (recaptcha === "") {
            event.preventDefault();
            alert("reCaptcha challenge failed. Please verify that you are human.");

        }
    });
});

function validateEnquiry() {
    var x = document.forms["enquiry_form"]["name"].value;
    var y = document.forms["enquiry_form"]["email"].value;
    var z = document.forms["enquiry_form"]["message"].value;

    if (x == null || x == "") {
        alert("Name field is required.");
        return false;
    } else if (y == null || y == "") {
        alert("Email field is required.");
        return false;
    } else if (z == null || z == "") {
        alert("Message field is required.");
        return false;
    } else {
        return true;
    }

    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,15})+$/.test(document.enquiry.email)) {
        return (true)
    } else {
        alert("Email address format is invalid. Please enter a valid email address.")
        return (false)
    }
}

$(function() {

    // Get the form.
    var form = $('enquiry_form');

    // Get the messages div.
    var formMessages = $('form-messages');

    // Set up an event listener for the contact form.
    $(form).submit(function(e) {
        // Stop the browser from submitting the form.
        e.preventDefault();

        // Serialize the form data.
        var formData = $(form).serialize();

        // Submit the form using AJAX.
        $.ajax({
                type: 'POST',
                url: $(form).attr('action'),
                data: formData
            })
            .done(function(response) {
                // Make sure that the formMessages div has the 'success' class.
                $(formMessages).removeClass('error');
                $(formMessages).addClass('success');

                // Set the message text.
                $(formMessages).text(response);

                // Clear the form.
                $('name').val('');
                $('email').val('');
                $('contact').val('');
                $('product').val('');
                $('message').val('');
            })
            .fail(function(data) {
                // Make sure that the formMessages div has the 'error' class.
                $(formMessages).removeClass('success');
                $(formMessages).addClass('error');

                // Set the message text.
                if (data.responseText !== '') {
                    $(formMessages).text(data.responseText);
                } else {
                    $(formMessages).text('An error occured and your message could not be sent. Please try again.');
                }
            });

    });

});