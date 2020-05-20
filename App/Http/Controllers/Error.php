<?php


namespace App\Http\Controllers;


class Error extends Controller
{
    public function error404(array $e)
    {
        return $this->render('error', ['errors' => $this->getErrors($e)]);
    }

    public function error500(array $e)
    {
        return $this->render('error', ['errors' => $this->getErrors($e)]);
    }

    private function getErrors(array $e)
    {
        return array_map(function (\Exception $e) {
            return [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }, $e);
    }
}
