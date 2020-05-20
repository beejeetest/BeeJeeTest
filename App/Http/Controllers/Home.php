<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRouteException;
use App\Exceptions\MethodNotAllowedException;
use App\Helpers\Paginator;
use App\Helpers\Sorter;
use App\Models\Task;

class Home extends Controller
{
    public function index($id = null)
    {
        $sorter = new Sorter('sort', ['username', 'email', 'id'], 'username desc');
        $pages = new Paginator('3', 'p', 'pagination-sm');
        $pages->set_total(Task::count());
        $data['records'] = Task::find('all', array_merge($sorter->getSort(), $pages->get_limit_keys()));
        $data['page_links'] = $pages->page_links('?', $sorter->getCurrentSort());

        $task = null;

        if ($id) {
            try {
                $task = Task::find($id);
            } catch (\Exception $e) {
                throw new InvalidRouteException('Task not found');
            }
        }

        return $this->render('Home', compact('data', 'sorter', 'task'));
    }

    public function update($id)
    {
        if (empty($_SESSION['user'])) {
            header('Location: /auth/login');
            return;
        }

        if (!$data = $_POST) {
            throw new MethodNotAllowedException();
        }

        if (!($id && $todo = Task::find($id))) {
            throw new InvalidRouteException('task not found');
        }

        $data['username'] = $this->cleanField($data['username']);
        $data['email'] = $this->cleanField($data['email']);
        $data['text'] = $this->cleanField($data['text']);

        if (!$errors = $this->taskValidate($data)) {
            $todo->edit($data['username'], $data['email'], $data['text'], ($data['text'] !== $todo->text ? $_SESSION['user']['username'] : null));
            if ($todo->save()) {
                $this->setFlash('success', ["Задача №: {$todo->id}" => 'Успешно обновлена!']);
            }
        } else {
            $this->setFlash('error', $errors);
            header("Location: /home/index/{$todo->id}");
            return;
        }
        header('Location: /');
    }

    public function complete($id)
    {
        if (!$_SESSION['user']) {
            header('Location: /auth/login');
            return;
        }

        if (!($id && $todo = Task::find($id))) {
            throw new InvalidRouteException('task not found');
        }

        $todo->statusComplete();
        if ($todo->save()) {
            $this->setFlash('success', ['status' => 'изменен на выполнено!']);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function create()
    {
        if (!$data = $_POST) {
            throw new MethodNotAllowedException();
        }
        $data['username'] = $this->cleanField($data['username']);
        $data['email'] = $this->cleanField($data['email']);
        $data['text'] = $this->cleanField($data['text']);

        if (!$errors = $this->taskValidate($data)) {
            $todo = Task::newTodo($data['username'], $data['email'], $data['text']);
            if ($todo->save()) {
                $this->setFlash('success', ["Задача №: {$todo->id}" => 'Успешно добавлено!']);
            }
        } else {
            $this->setFlash('error', $errors);
        }
        header('Location: /');
    }


    private function taskValidate(array $post)
    {
        $errors = [];
        if (empty($post['username'])) {
            $errors['username'] = 'Обязателен для заполнения!';
        }
        if (empty($post['email'])) {
            $errors['email'] = 'Обязателен для заполнения!';
        }
        if (empty($post['text'])) {
            $errors['text'] = 'Обязателен для заполнения!';
        }
        if (!empty($post['email']) && !$this->emailValidation($post['email'])) {
            $errors['email'] = 'Неправильный формат эл.почты!';
        }
        return $errors;
    }

    private function emailValidation(string $email)
    {
        $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        return preg_match($pattern, $email);
    }
}
