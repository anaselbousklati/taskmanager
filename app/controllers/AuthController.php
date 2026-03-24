<?php


class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

  
    public function loginForm(): void
    {
        if (isLoggedIn()) redirect('tasks');
        require APP . '/views/auth/login.php';
    }

 
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }

        validateCsrf();

        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Vul alle velden in.';
            require APP . '/views/auth/login.php';
            return;
        }

        $user = $this->userModel->verify($email, $password);

        if (!$user) {
            $error = 'E-mailadres of wachtwoord is onjuist.';
            require APP . '/views/auth/login.php';
            return;
        }

        session_regenerate_id(true);

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        redirect('tasks');
    }
    public function registerForm(): void
    {
        if (isLoggedIn()) redirect('tasks');
        require APP . '/views/auth/register.php';
    }

  
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('register');
        }

        validateCsrf();

        $name            = trim($_POST['name'] ?? '');
        $email           = strtolower(trim($_POST['email'] ?? ''));
        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $errors          = [];

        if (empty($name) || mb_strlen($name) < 2) {
            $errors[] = 'Naam moet minimaal 2 tekens bevatten.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Ongeldig e-mailadres.';
        }

        if (mb_strlen($password) < 8) {
            $errors[] = 'Wachtwoord moet minimaal 8 tekens bevatten.';
        }

        if ($password !== $passwordConfirm) {
            $errors[] = 'Wachtwoorden komen niet overeen.';
        }

        if (!empty($errors)) {
            $old = $_POST;
            require APP . '/views/auth/register.php';
            return;
        }

        $created = $this->userModel->create(
            sanitize($name),
            $email,
            $password
        );

        if (!$created) {
            $errors[] = 'Dit e-mailadres is al in gebruik.';
            $old      = $_POST;
            require APP . '/views/auth/register.php';
            return;
        }

        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Account aangemaakt! Je kunt nu inloggen.'];
        redirect('login');
    }


    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        redirect('login');
    }
}
