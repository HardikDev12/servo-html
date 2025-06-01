<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php'; // Use Composer autoload

header('Content-Type: application/json');

// Server-side validation
$errors = [];

// Validate name
$name = trim($_POST['name'] ?? '');
if (empty($name) || strlen($name) < 2) {
    $errors[] = 'Please enter your full name';
}

// Validate phone
$phone = trim($_POST['phone'] ?? '');
if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) {
    $errors[] = 'Please enter a valid 10-digit mobile number';
}

// Validate address
$address = trim($_POST['address'] ?? '');
if (empty($address) || strlen($address) < 5) {
    $errors[] = 'Please enter your complete service address';
}

// Validate brand
$brand = trim($_POST['brand'] ?? '');
if (empty($brand)) {
    $errors[] = 'Please select your appliance brand';
}

// Validate appliance
$appliance = trim($_POST['appliance'] ?? '');
if (empty($appliance)) {
    $errors[] = 'Please select the type of appliance';
}

// Validate problem description
$problem = trim($_POST['problem'] ?? '');
if (empty($problem) || strlen($problem) < 10) {
    $errors[] = 'Please provide a detailed description of the problem (minimum 10 characters)';
}

// Validate email
$email = trim($_POST['email'] ?? '');
if (empty($email)) {
    $errors[] = 'Please enter your email address';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address';
}

// If there are validation errors, return them
if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'errors' => $errors,
        'message' => 'Please check your details and try again'
    ]);
    exit;
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'repairservicesameday@gmail.com';
    $mail->Password   = 'hmtkrwtqzcjdqkf';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('repairservicesameday@gmail.com', 'Repair Service Same Day');
    $mail->addAddress($email ?: 'customer@email.com', $name);
    $mail->addReplyTo('repairservicesameday@gmail.com', 'Repair Service Same Day');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your Repair Service Same Day Booking Confirmation';
    $mail->Body    = "
    <div style=\"font-family: Poppins, Arial, sans-serif; background: #f6f8fb; padding: 0; margin: 0;\">
      <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #f6f8fb; padding: 0; margin: 0;\">
        <tr>
          <td align=\"center\">
            <table width=\"600\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); margin: 40px 0;\">
              <tr>
                <td style=\"background: #2563eb; border-radius: 16px 16px 0 0; padding: 32px 0 32px 40px; text-align: left;\">
                  <img src=\"https://repairservicesameday.com/assets/servo-logo.png\" alt=\"Repair Service Same Day Logo\" style=\"height: 48px; vertical-align: middle;\">
                  <span style=\"font-size: 2rem; color: #fff; font-weight: 700; letter-spacing: 1px; margin-left: 18px; vertical-align: middle;\">
                    Repair Service Same Day
                  </span>
                </td>
              </tr>
              <tr>
                <td style=\"padding: 32px 40px 16px 40px; text-align: left;\">
                  <h2 style=\"color: #2563eb; margin: 0 0 12px 0; font-size: 1.5rem;\">Booking Confirmation</h2>
                  <p style=\"color: #222; font-size: 1.1rem; margin: 0 0 24px 0;\">Thank you for booking your appliance repair with <b>Repair Service Same Day</b>! Here are your booking details:</p>
                  <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background: #f6f8fb; border-radius: 8px; margin-bottom: 24px;\">
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600; width: 160px;\">Name:</td><td style=\"padding: 10px 16px; color: #222;\">$name</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Phone:</td><td style=\"padding: 10px 16px; color: #222;\">$phone</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Address:</td><td style=\"padding: 10px 16px; color: #222;\">$address</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Brand:</td><td style=\"padding: 10px 16px; color: #222;\">$brand</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Appliance:</td><td style=\"padding: 10px 16px; color: #222;\">$appliance</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Problem:</td><td style=\"padding: 10px 16px; color: #222;\">$problem</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Email:</td><td style=\"padding: 10px 16px; color: #222;\">$email</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #2563eb; font-weight: 700;\">Service Charges:</td><td style=\"padding: 10px 16px; color: #2563eb; font-weight: 700;\">&#8377;199</td></tr>
                  </table>
                  <p style=\"color: #222; font-size: 1.05rem; margin-bottom: 0;\">Our team will contact you soon to confirm your appointment.<br>We look forward to serving you!</p>
                </td>
              </tr>
              <tr>
                <td style=\"background: #f6f8fb; border-radius: 0 0 16px 16px; padding: 24px 40px; text-align: center; color: #888; font-size: 0.95rem;\">
                  <div style=\"margin-bottom: 8px;\">
                    <b>Repair Service Same Day</b> &bull; +91 8460725334 &bull; repairservicesameday@gmail.com
                  </div>
                  <div>123 Service Street, Ahmedabad</div>
                  <div style=\"margin-top: 8px; color: #bbb; font-size: 0.9em;\">&copy; ".date('Y')." Repair Service Same Day. All rights reserved.</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>";

    $mail->send();

    // Send admin notification
    $adminMail = new PHPMailer(true);
    $adminMail->isSMTP();
    $adminMail->Host       = 'smtp.gmail.com';
    $adminMail->SMTPAuth   = true;
    $adminMail->Username   = 'repairservicesameday@gmail.com';
    $adminMail->Password   = 'hmtkrwtqzcjdqkf';
    $adminMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $adminMail->Port       = 587;
    $adminMail->setFrom('repairservicesameday@gmail.com', 'Repair Service Same Day');
    $adminMail->addAddress('repairservicesameday@gmail.com', 'Repair Service Same Day Admin');
    $adminMail->isHTML(true);
    $adminMail->Subject = 'New Booking Received - Repair Service Same Day';
    $adminMail->Body    = "
    <div style=\"font-family: Poppins, Arial, sans-serif; background: #f6f8fb; padding: 0; margin: 0;\">
      <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #f6f8fb; padding: 0; margin: 0;\">
        <tr>
          <td align=\"center\">
            <table width=\"600\" cellpadding=\"0\" cellspacing=\"0\" style=\"background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); margin: 40px 0;\">
              <tr>
                <td style=\"background: #2563eb; border-radius: 16px 16px 0 0; padding: 32px 0 32px 40px; text-align: left;\">
                  <img src=\"https://repairservicesameday.com/assets/servo-logo.png\" alt=\"Repair Service Same Day Logo\" style=\"height: 48px; vertical-align: middle;\">
                  <span style=\"font-size: 2rem; color: #fff; font-weight: 700; letter-spacing: 1px; margin-left: 18px; vertical-align: middle;\">
                    Repair Service Same Day
                  </span>
                </td>
              </tr>
              <tr>
                <td style=\"padding: 32px 40px 16px 40px; text-align: left;\">
                  <h2 style=\"color: #2563eb; margin: 0 0 12px 0; font-size: 1.5rem;\">New Booking Received</h2>
                  <p style=\"color: #222; font-size: 1.1rem; margin: 0 0 24px 0;\">A new booking has been submitted. Here are the details:</p>
                  <table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"background: #f6f8fb; border-radius: 8px; margin-bottom: 24px;\">
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600; width: 160px;\">Name:</td><td style=\"padding: 10px 16px; color: #222;\">$name</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Phone:</td><td style=\"padding: 10px 16px; color: #222;\">$phone</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Email:</td><td style=\"padding: 10px 16px; color: #222;\">$email</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Address:</td><td style=\"padding: 10px 16px; color: #222;\">$address</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Brand:</td><td style=\"padding: 10px 16px; color: #222;\">$brand</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Appliance:</td><td style=\"padding: 10px 16px; color: #222;\">$appliance</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #555; font-weight: 600;\">Problem:</td><td style=\"padding: 10px 16px; color: #222;\">$problem</td></tr>
                    <tr><td style=\"padding: 10px 16px; color: #2563eb; font-weight: 700;\">Service Charges:</td><td style=\"padding: 10px 16px; color: #2563eb; font-weight: 700;\">&#8377;199</td></tr>
                  </table>
                  <p style=\"color: #222; font-size: 1.05rem; margin-bottom: 0;\">Please follow up with the customer to confirm the appointment.</p>
                </td>
              </tr>
              <tr>
                <td style=\"background: #f6f8fb; border-radius: 0 0 16px 16px; padding: 24px 40px; text-align: center; color: #888; font-size: 0.95rem;\">
                  <div style=\"margin-bottom: 8px;\">
                    <b>Repair Service Same Day</b> &bull; +91 8460725334 &bull; repairservicesameday@gmail.com
                  </div>
                  <div>123 Service Street, Ahmedabad</div>
                  <div style=\"margin-top: 8px; color: #bbb; font-size: 0.9em;\">&copy; ".date('Y')." Repair Service Same Day. All rights reserved.</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>";
    $adminMail->send();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
}
