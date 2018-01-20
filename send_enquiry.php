<?php
    session_start();

    $_SESSION['form_submitted'] = $_POST['form_submitted'];

    $captcha;

        if (isset($_POST['g-recaptcha-response'])) {
            $captcha = $_POST['g-recaptcha-response'];
        }

        if (!$captcha) {
            $error_msg = "reCaptcha challenge failed. Please check the reCaptcha again and verify you are not a robot.";
            echo "<script type='text/javascript'>alert('$error_msg');</script>";
            header("Refresh: 5; url=index.html");
            exit();
        } else {

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get the form fields and remove whitespace.
                $name = strip_tags(trim($_POST["name"]));
                $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
                $contact = strip_tags(trim($_POST["contact"]));
                $contact = str_replace(array("\r", "\n"), array(" ", " "), $contact);
                $product = strip_tags(trim($_POST["product"]));
                $product = str_replace(array("\r", "\n"), array(" ", " "), $product);
                $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
                $message = trim($_POST["message"]);

                // Check that data was sent to the mailer.
                if (empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Set a 400 (bad request) response code and exit.
                    http_response_code(400);
                    echo "There was a problem with your submission. Please complete the form and try again.";
                    exit;
                }

                // Set the recipient email address.
                $recipient = "info@machine-rebuilding.com";

                $subject = "machine-rebuilding.com - New enquiry from $name <$email>";

                $email_content = "Name: $name\n";
                $email_content .= "Email: $email\n\n";
                $email_content .= "Contact Number: $contact\n\n";
                $email_content .= "Product/Service: $product\n\n";
                $email_content .= "Message:\n$message\n";

                $email_headers = "From: $name <$email>";

                // Send the email.
                if (mail($recipient, $subject, $email_content, $email_headers)) {
                    // Set a 200 (okay) response code.
                    http_response_code(200);
                    echo "Your enquiry has been sent.";
                } else {
                    // Set a 500 (internal server error) response code.
                    http_response_code(500);
                    echo "Something went wrong and we couldn't send your message. Please try again.";
                }
            } else {
                // Not a POST request, set a 403 (forbidden) response code.
                http_response_code(403);
                echo "There was a problem with your submission, please try again.";
            }
    }
?>
