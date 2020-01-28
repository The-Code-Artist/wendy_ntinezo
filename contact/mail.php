<?php
/*
 * Title		: Mail
 * Date			: 07-Aug-2018
 * Author		: Marothi Mahlake
 * Description	: Process an AJAX request from an HTML contact form and send an email to WENDY.
 * File name	: mail.php
 */

// TODO: Include the PHPMailer library into the script.
require("../php mailer/PHPMailerAutoload.php");

// TODO: Process the form on POST request.
$request_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
if ($request_method == "POST") {

	// TODO: Ensure that an AJAX request was used to submit the form.
	$request_type = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');
	if (!isset($request_type) && strtolower($request_type) != 'xmlhttprequest') {
		$output = json_encode(array('text' => 'Please submit the form via AJAX request.'));
		die($output);
	}

	// TODO: Retrieve submitted data and store in to respective variables.
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
	$subject = filter_input(INPUT_POST, 'subject');
	$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

	// TODO: Perform server side validation to enforce security.
	// TODO: Validate the name.
	if (empty($name)) {
		$output = json_encode(array(
			'text' => 'Please enter your name.'));
		die($output);
	} elseif (strlen($name) < 4) {
		$output = json_encode(array(
			'text' => 'The name must at least contain 4 characters.'));
		die($output);
	}
	// TODO: Validate the email address.
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$output = json_encode(array(
			'text' => 'Please enter a valid email address.'));
		die($output);
	} elseif (empty($email)) {
		json_encode(array(
			'text' => 'Please enter your email address.'));
		die($output);
	} elseif (strlen($email) < 7) {
		json_encode(array(
			'text' => 'The email address must at least contain 7 characters.'));
		die($output);
	}
	// TODO: Validate the phone number.
	if (empty($phone)) {
		json_encode(array(
			'text' => 'Please enter your phone number.'));
		die($output);
	} elseif (strlen($phone) < 10) {
		json_encode(array(
			'text' => 'The contact number must at least contain 10 characters.'));
		die($output);
	}

	// TODO: Ensure that the subject field is not empty.
	if (empty($subject)) {
		json_encode(array(
			'text' => 'Please type in the message.'));
		die($output);
	} elseif (strlen($subject) < 5) {
		$output = json_encode(array('text' => 'The subject must at least contain 5 characters.'));
		die($output);
	}

	// TODO: Ensure that the message field is not empty.
	if (empty($message)) {
		json_encode(array(
			'text' => 'Please type in the message.'));
		die($output);
	} elseif (strlen($message) < 20) {
		$output = json_encode(array('text' => 'The message must at least contain 20 characters.'));
		die($output);
	}

	// TODO: Instantiate a new PHPMailer object.
	$mail = new PHPMailer;

	// Set all the email parameters.
	$mail->isSMTP();
	$mail->Host = "smtp.gmail.com";
	$mail->Username = "hlakzin.m@gmail.com";
	$mail->Password = "vzp46@BXD$";
	$mail->setFrom("conversationwnd@gmail.com", "Wendy Online");
	$mail->addAddress("wendythembi@gmail.com");
	$mail->Port = 587;
	$mail->SMTPSecure = "tls";
	$mail->SMTPAuth = true;
	$mail->isHTML(true);
	$mail->Subject = "Website generated email by: $name";
	$mail->msgHTML = "<p><strong>Name:</strong> $name</p><p><strong>Email:</strong> $email</p><p><strong>Contact No:</strong> $phone</p><p><strong>Message:</strong> $message</p>";
	$mail->Body = $mail->msgHTML;

	// TODO: Send the email & output a response message.
	if($mail->send()) {
		$output = json_encode(array(
			'text' => 'Your message has successfully been sent to Wendy.'));
		die($output);
	} else {
		$output = json_encode(array(
			'text' => 'Sorry, your message could not be delivered to Wendy.'));
		die($output);
	}
}
?>
