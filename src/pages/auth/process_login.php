<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $url = 'http://localhost:3000/auth/login'; // Node.js server URL

    $data = array(
        'email' => $email,
        'password' => $password,
    );

    $options = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ),
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === FALSE) {
        $_SESSION['error'] = 'Wrong Credentials';
        header('Location: ./login.php');
        exit;
    }

    $response = json_decode($result, true);

    if (isset($response['token'])) {
        // Store the JWT token in session or cookies
        $_SESSION['token'] = $response['token'];
        $_SESSION['username']= $response['username'];
        $_SESSION['role']= $response['role'];
        header('Location: ../../index.php');
        exit;
    } else {
        echo '<p>Login failed: ' . htmlspecialchars($response['error']) . '</p>';
    }
}
?>
