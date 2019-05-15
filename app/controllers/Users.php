<?php

Class Users extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function register()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirme_password' => trim($_POST['confirme_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirme_password_err' => ''
            ];
            // Validate Email
            if (empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } else {
                // Check email
                if ($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'email is already taken';
                }
            }

            // Validate name
            if (empty($data['name'])){
                $data['name_err'] = 'Please enter name';
            }

            // Validate password
            if (empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            } else if(strlen($data['password']) < 8){
                $data['password_err'] = 'Password must be at least 8 characters';
            }

            // Validate confirme_password
            if (empty($data['confirme_password'])){
                $data['confirme_password_err'] = 'Please confirm password';
            } else {
                if ($data['confirme_password'] != $data['password']){
                    $data['confirme_password_err'] = 'password do not match';
                }
            }
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirme_password_err'])){
                // hash Password 
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // register user
                if ($this->userModel->register($data)){
                    flash('register_success', 'You are Registered and can log in');
                    // redirect to success register
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }
        } else {
            // Init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirme_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirme_password_err' => ''
            ];

            // Load view
            $this->view('users/register', $data);
        }
    }

    public function login()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Process form
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }
            
            // Validate password
            if (empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])){
                // User found
            } else {
                // email not found
                $data['email_err'] = 'No user found'; 
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])){
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                if ($loggedInUser){
                    // create session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password Incorrect';
                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('users/login', $data);
        }
    }

    // create user session
    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['name'] = $user->name;
        redirect('posts');
    }

    // Log out
    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['name']);
        session_destroy();
        redirect('users/login');
    }
}

?>
