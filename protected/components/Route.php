<?php

class Route implements IComponent {
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    public $routes;
    public $default;

    // todo add some good name
    private static $routestmp = [
        'GET' => [],
        'POST' => [],
    ];

    public function init() {
        $this->routes = self::$routestmp;
    }

    public static function GET($pattern, $callable) {
        self::$routestmp[self::METHOD_GET][$pattern] = $callable;
    }

    public static function POST($pattern, $callable) {
        self::$routestmp[self::METHOD_GET][$pattern] = $callable;
    }

    public function process($route, $verb, $default) {
        $matchedPattern = $this->findPattern($route, $verb, $default);

        if($matchedPattern === '') {
            throw new AppHttpException(404);
        }

        $patternData = $this->routes[$verb][$matchedPattern];
        $routeVars = $this->extractVarsFromRoute($matchedPattern, $route);

        if(is_callable($patternData)) {
            $return = call_user_func_array($patternData, $routeVars);
            if(!empty($return)) {
                Rest::sendResponse($return);
            }
            return ;
        }

        throw new AppHttpException(404);
    }

    public function compareItem($patternStr, $requestStr) {

        if(strpos($patternStr,':') !== false) {
            // id[0] = \d+123
            $explodedPattern = explode(':', $patternStr);

            return preg_match('#'.$explodedPattern[1].'#', $requestStr) === 1;
        }

        return $patternStr === $requestStr;
    }

    public function findPattern($route, $verb, $default = '') {
        if(strlen($route) < 1) {
            return $default;
        }

        $params = $this->filterParams(explode('/', $route));

        $matchedPattern = '';

        foreach($this->routes[$verb] as $pattern => $data) {
            $patternParams = $this->filterParams(explode('/', $pattern));

            if(count($patternParams) !== count($params)) {
                continue;
            }

            for($i = 0; $i < count($params); $i++) {
                if(!$this->compareItem($patternParams[$i], $params[$i])) {
                    continue(2);
                }
            }

            $matchedPattern = $pattern;
        }

        return $matchedPattern;
    }

    private function filterParams($params) {
        return array_filter($params, function($item) {
            if(strlen($item) < 1) {
                return false;
            }

            return true;
        });

    }

    public function extractVarsFromRoute($pattern, $route) {
        $patternExploded = $this->filterParams(explode('/', $pattern));
        $routeExploded = $this->filterParams(explode('/', $route));

        $vars = [];
        for($i = 0, $n = count($routeExploded); $i < $n; $i++) {
            if(strpos($patternExploded[$i], ':') === false) {
                continue;
            }

            $varName = explode(':', $patternExploded[$i])[0];
            $varValue = $routeExploded[$i];
            $vars[$varName] = $varValue;
        }

        return $vars;
    }
}