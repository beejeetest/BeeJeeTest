<?php

namespace App\Http\Controllers;

use App\Models\User;

class Auth extends Controller
{
    public function login()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
        }
        if ($_POST) {
            if (empty($_POST['username'])) {
                $errors['username'] = 'Обязателен для заполнения!';
            }
            if (empty($_POST['password'])) {
                $errors['password'] = 'Обязателен для заполнения!';
            }
            if (($username = $this->cleanField($_POST['username'])) && ($password = $this->cleanField($_POST['password']))) {
                if (!$user = User::find('first', ['username' => $username, 'password' => md5($password)])) {
                    $errors['username or password'] = 'Неправильное имя пользователя или пароль.';
                } else {
                    /**
                     * @var User $user
                     */
                    $session = $_SESSION;
                    $session = array_merge($session, ['user' => ['username' => $user->username]]);
                    $_SESSION = $session;
                    $this->setFlash('success', [$user->username => 'Вы успешно ввошли в систему.']);
                    header('Location: /');
                }
            }
            if ($errors) {
                $this->setFlash('error', $errors);
            }
        }
        return $this->render('login');
    }


    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: /');
    }
}
