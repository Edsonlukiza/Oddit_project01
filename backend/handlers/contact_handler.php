<?php
session_start();
header( 'Content-Type: application/json' );

include __DIR__ . '/../config/db_connect.php';

// Handle POST request
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $name = trim( $_POST[ 'name' ] ?? '' );
    $email = trim( $_POST[ 'email' ] ?? '' );
    $message = trim( $_POST[ 'message' ] ?? '' );

    // Validation
    $errors = [];

    if ( empty( $name ) ) {
        $errors[] = 'Name is required';
    }

    if ( empty( $email ) ) {
        $errors[] = 'Email is required';
    } elseif ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        $errors[] = 'Please enter a valid email address';
    }

    if ( empty( $message ) ) {
        $errors[] = 'Message is required';
    } elseif ( strlen( $message ) < 10 ) {
        $errors[] = 'Message must be at least 10 characters long';
    }

    if ( !empty( $errors ) ) {
        echo json_encode( [
            'success' => false,
            'errors' => $errors
        ] );
        exit;
    }

    // Check if contacts table exists, create if not
    $table_check = "CREATE TABLE IF NOT EXISTS contact_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        message LONGTEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('new', 'read', 'replied') DEFAULT 'new'
    )";

    mysqli_query( $conn, $table_check );

    // Insert message into database
    $stmt = $conn->prepare( 'INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)' );

    if ( !$stmt ) {
        echo json_encode( [
            'success' => false,
            'errors' => [ 'Database error: ' . $conn->error ]
        ] );
        exit;
    }

    $stmt->bind_param( 'sss', $name, $email, $message );

    if ( $stmt->execute() ) {
        // Get company email from settings
        $settings_result = mysqli_query( $conn, "SELECT setting_value FROM site_settings WHERE setting_key = 'company_email' LIMIT 1" );
        $company_email = 'admin@example.com';

        if ( $settings_result && $row = mysqli_fetch_assoc( $settings_result ) ) {
            $company_email = $row[ 'setting_value' ];
        }

        // Try to send email ( optional - requires mail server configuration )
        $to = $company_email;
        $subject = "New Contact Form Message from $name";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= 'Content-Type: text/plain; charset=UTF-8\r\n';

        $email_body = 'You have received a new contact form message.\n\n';
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Message: $message\n";
        $email_body .= '\nDate: ' . date( 'Y-m-d H:i:s' ) . '\n';

        // Send email ( may not work in local development )
        @mail( $to, $subject, $email_body, $headers );

        echo json_encode( [
            'success' => true,
            'message' => 'Thank you for your message! We will get back to you soon.'
        ] );
    } else {
        echo json_encode( [
            'success' => false,
            'errors' => [ 'Error saving message: ' . $stmt->error ]
        ] );
    }

    $stmt->close();
    exit;
}

// If not a POST request, return 405
http_response_code( 405 );
echo json_encode( [ 'success' => false, 'message' => 'Method not allowed' ] );
?>
