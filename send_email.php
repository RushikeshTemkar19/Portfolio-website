<?php
// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Include PHPMailer autoloader
    require 'vendor/autoload.php'; // Make sure you have PHPMailer installed via Composer
    
    // Get form data
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields']);
        exit;
    }
    
    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address']);
        exit;
    }
    
    // Setup PHPMailer
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->SMTPDebug = 0; // Set to 2 for debugging
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Change to your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'rushikeshtemkar19@gmail.com'; // Your Gmail address
        $mail->Password = 'yapv ghyo pfld nrea'; // Your app password (note: consider storing this in a more secure way)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Recipients
        $mail->setFrom('rushikeshtemkar19@gmail.com', 'Contact Form');
        $mail->addAddress('rushikeshtemkar19@gmail.com'); // Where to send the contact form messages
        $mail->addReplyTo($email, $name);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Contact Form: ' . $subject;
        
        // Email body
        $mail->Body = "
            <h2>New message from your website contact form</h2>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong></p>
            <p>{$message}</p>
        ";
        
        $mail->AltBody = "New message from {$name}\nEmail: {$email}\nPhone: {$phone}\nSubject: {$subject}\nMessage: {$message}";
        
        // Send email
        $mail->send();
        
        // Return success response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Your message has been sent. Thank you!']);
    } catch (Exception $e) {
        // Return error response
        header('Content-Type: application/json');
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo json_encode(['status' => 'error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    // Not a POST request, return error
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
