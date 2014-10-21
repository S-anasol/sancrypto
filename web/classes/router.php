<?php
Class Router
{
    private $registry;
    private $path;
    private $args = array();

    function __construct()
    {
        if (is_dir(PATH_CONTROLLERS) == false)
        {
            throw new Exception('Invalid controller path: `' . $path . '`');
        }

        $this->path = PATH_CONTROLLERS;
    }

    private function getController(&$file, &$controller, &$action, &$args)
    {
        $route = (empty($_GET['route'])) ? '' : $_GET['route'];
        if (empty($route))
        {
            $route = 'index';
        }

        // Получаем раздельные части
        $route = trim($route, '/\\');
        $parts = explode('/', $route);

        // Находим правильный контроллер
        $cmd_path = $this->path;

        foreach ($parts as $part)
        {
            $fullpath = $cmd_path . $part;

            // Находим файл
            if (is_file($fullpath . '.php'))
            {
                $controller = $part;
                array_shift($parts);
                break;
            }
            else
            {
                die('Controller ' . $part . ' not found');
            }
        }

        if (empty($controller))
        {
            $controller = 'index';
        }

        // Получаем действие
        $action = array_shift($parts);

        if (empty($action))
        {
            $action = 'index';
        }

        $file = $cmd_path . $controller . '.php';
        $args = $parts;

    }

    function delegate()
    {
        // Анализируем путь
        $this->getController($file, $controller, $action, $args);

        // Файл доступен?
        if (is_readable($file) == false)
        {
            die('404 Not Found');
        }

        // Подключаем файл
        require_once ($file);

        // Создаём экземпляр контроллера
        $GLOBALS["cur_controller"] = $controller;
        $class = 'Controller_' . $controller;
        $controller = new $class();

        // Действие доступно?
        if (is_callable(array($controller, $action)) == false)
        {
            die('Action ' . $action . ' not found');
        }
        $GLOBALS["get"] = $args;
        $GLOBALS["cur_action"] = $action;
        // Выполняем действие
        $controller->$action();
    }

    function redirect($path, $terminate = false, $params = array())
    {
		$path = PATH_BASE.'/'.$path."?".http_build_query($params);
        header( "Location: {$path}", true, 301 );
        if($terminate)
        {
            die();
        }
    }

}
?>
