        <?php
        session_start();
        require_once __DIR__. '/../middleware/auth.php';

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username= $_POST['username'];
            $email= $_POST['email'];
            $password= $_POST['password'];
            $confirm_password= $_POST['confirm_password'];

            $validation = new checks($username, $email, $password, $confirm_password);
            $validationResult= $validation->validateAll();

            if(is_array($validationResult)){
                $_SESSION['errors'] = $validationResult;
                header('Location: ../auth/register.php');
                exit;
            }
            else{
                $url = 'http://localhost:3000/auth/register';

                $data = array(
                    'username' => $username,
                    'password' => $password,
                    'email' => $email,
                    'role' => 'owner',
                );

                $ch= curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                $result = curl_exec($ch);

                if ($result === FALSE) {
                    $_SESSION['error'] = 'Error communicating with the server';
                    curl_close($ch);
                    header('Location: ../auth/register.php');
                    exit;
                }

                $response = json_decode($result, true);

                if(isset($response['error'])){
                    $_SESSION['error']= $response['error'];
                    header('Location: ../auth/register.php');
                    exit;
                }
                else{
                    header('Location: ../auth/login.php');
                    exit;
                }
            }


        }
