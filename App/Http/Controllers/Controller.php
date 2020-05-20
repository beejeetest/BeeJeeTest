<?php

namespace App\Http\Controllers;

class Controller
{
    public $layoutFile = 'Layout/Layout.php';
    const NO_LAYOUT = false;

    public function renderLayout($body)
    {
        ob_start();
        require VIEW_PATH . DIRECTORY_SEPARATOR . $this->layoutFile;
        return ob_get_clean();
    }

    public function render($viewName, array $params = [])
    {

        $viewFile = VIEW_PATH . DIRECTORY_SEPARATOR . $viewName . '.php';
        extract($params);
        ob_start();
        require $viewFile;
        $body = ob_get_clean();
        if (ob_get_contents()) ob_end_clean();
        if (defined(self::NO_LAYOUT)) {
            return $body;
        }
        return $this->renderLayout($body);

    }

    protected function cleanField($field)
    {
        $field = strip_tags($field);
        $field = trim($field);
        $field = stripslashes($field);
        $field = htmlspecialchars($field);
        return $field;
    }

    /**
     * @param string $type / error, success or warning
     * @param $messages
     */
    protected function setFlash($type, array $messages)
    {
        $_SESSION['flash'] = [$type => $messages];
    }
}
