<?php

namespace core;

use GuzzleHttp\Psr7\Header;

class BaseController
{
    protected $layot;

    public function render($view, array $params = [])
    {
        $className = lcfirst(
            str_replace(
                'Controller',
                '',
                substr(
                    get_class($this),
                    strpos(
                        get_class($this),
                        '\\'
                    ) + 1
                )
            )
        );

        $viewPath = './views/' . $className . '/' . $view . '.php';
        if (file_exists($viewPath)) {
            if ($this->layot) {
                require_once './views/_shared/header.php';
            }
            // extract($params) преобразует массив в переменные, 
            // где название переменной это ключ массива
            extract($params);
            require_once $viewPath;
            if ($this->layot) {
                require_once './views/_shared/footer.php';
            }
        } else {
            require_once './views/_shared/error.php';
        }
    }

    public function redirect($path)
    {
        Header('Location: ' . $path);
    }
}
