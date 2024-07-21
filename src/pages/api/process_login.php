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

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $result = curl_exec($ch);

    if ($result === FALSE) {
        $_SESSION['error'] = 'Error communicating with the server';
        curl_close($ch);
        header('Location: ../auth/login.php');
        exit;
    }

    $response = json_decode($result, true);

    if (isset($response['error'])) {
        $_SESSION['error'] = $response['error'];
        curl_close($ch);
        header('Location: ../auth/login.php');
        exit;
    }

    if (isset($response['token'])) {
        // Store the JWT token in session or cookies
        $_SESSION['token'] = $response['token'];
        $_SESSION['username'] = $response['username'];
        $_SESSION['role'] = $response['role'];
        curl_close($ch);
        header('Location: ../../index.php');
        exit;
    } else {
        $_SESSION['error'] = 'Login failed: Unexpected response from server';
        curl_close($ch);
        header('Location: ../auth/login.php');
        exit;
    }
}
?>
