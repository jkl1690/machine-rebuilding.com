<?php

include 'config.php';

$captcha;

if (isset($_POST['g-recaptcha-response'])) {
    $captcha = $_POST['g-recaptcha-response'];
}

if (!$captcha) {
    $error_msg = "reCaptcha challenge failed. Please check the reCaptcha again and verify you are not a robot.";
    echo "<script type='text/javascript'>alert('$error_msg');</script>";
    header("Refresh: 3; url=index.html");
    exit();
} else {

    if(isset($_POST['email'])) {

        function died($error) {
            echo "Sorry, but there were error(s) found with the form you submitted. ";
            echo "These errors appear below.<br /><br />";
            echo $error."<br /><br />";
            echo "Please go back and fix these errors.<br /><br />";
            die();
        }

        if(!isset($_POST['name']) ||
            !isset($_POST['email']) ||
            !isset($_POST['contact']) ||
            !isset($_POST['product']) ||
            !isset($_POST['message'])
        ) {
            died('Sorry, there appears to be a problem with your form submission. Please try again.');
            header('Refresh: 5;');
        }

        $full_name = $_POST['name']; // required
        $email_from = $_POST['email']; // required
        $telephone = $_POST['contact']; // not required
        $product = $_POST['product']; // not required
        $comments = $_POST['message']; // required

        $error_message = "";

        $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
        if(preg_match($email_exp,$email_from)==0) {
            $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
        }
        if(strlen($full_name) < 2) {
            $error_message .= 'Your Name does not appear to be valid.<br />';
        }
        if(strlen($comments) < 2) {
            $error_message .= 'The message you entered do not appear to be valid.<br />';
        }


        if(strlen($error_message) > 0) {
            died($error_message);
        }

        $email_message = "A new enquiry has been sent from $full_name. <$email_from>\r\n\n";

        function clean_string($string) {
            $bad = array("content-type","bcc:","to:","cc:");
            return str_replace($bad,"",$string);
        }

        $email_message .= "Name: ".clean_string($full_name)."\r\n";
        $email_message .= "Email: ".clean_string($email_from)."\r\n";
        $email_message .= "Contact Number: ".clean_string($telephone)."\r\n";
        $email_message .= "Product/Service: ".clean_string($product)."\r\n\n";
        $email_message .= "Message: ".clean_string($comments)."\r\n";

        $email_subject = "machine-rebuilding.com - New enquiry from $full_name <$email_from>";

        $headers = 'From: '.$email_from."\r\n".
            'Reply-To: '.$email_from."\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($email_to, $email_subject, $email_message, $headers);
        header("Location: $thankyou");

        ?>
        <script>location.replace('<?php echo $thankyou;?>')</script>
        <?php
    }
}
die();
?>