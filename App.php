<?php

spl_autoload_register(function ($class) {
    if(file_exists(__DIR__."/protected/components/{$class}.php")) {
        require_once __DIR__."/protected/components/{$class}.php";
    }

    if(file_exists(__DIR__."/protected/exceptions/{$class}.php")) {
        require_once __DIR__."/protected/exceptions/{$class}.php";
    }

    if(file_exists(__DIR__."/protected/helpers/{$class}.php")) {
        require_once __DIR__."/protected/helpers/{$class}.php";
    }
});

/**
 * Class App
 * @property Database $database
 * @property Route $route
 * @property Rest $rest
 *
 */
class App {
    private $components;

    private static $_i;

    public static function getI() {
        if(self::$_i === null) {
            throw new AppSystemException('Empty instance');
        }

        return self::$_i;
    }

    public function __construct($config)
    {
        foreach($config['components'] as $className => $params) {
            $this->setComponent($className, $params);
        }

        self::$_i = $this;
    }

    private function setComponent($name, $params) {
        $className = ucfirst($name);
        /**
         * @var $obj IComponent
         */
        $obj = new $className;
        foreach($params as $property => $value) {
            $obj->$property = $value;
        }

        $obj->init();
        $this->components[$name] = $obj;
    }

    public function __get($name)
    {
        if(isset($this->components[$name])) {
            return $this->components[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Неопределенное свойство в __get(): ' . $name .
            ' в файле ' . $trace[0]['file'] .
            ' на строке ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }


    public function run() {
        $path = '';
        if(isset($_SERVER['PATH_INFO']) && strlen($_SERVER['PATH_INFO']) > 1) {
            $path = ltrim($_SERVER['PATH_INFO'], '/');
        }
        $this->route->process($path, $_SERVER['REQUEST_METHOD'], $this->route->default);
    }
}