<?php

namespace LumenPress\WordPressRouter;

use FastRoute\Dispatcher;
use Illuminate\Http\Request;
use LumenPress\Nimble\Models\Post;
use LumenPress\Nimble\Models\Taxonomy;
use LumenPress\Nimble\Models\User;

class WordPressRouter
{
    protected $middleware = [];

    protected $routes = [];

    public function __construct()
    {
        $this->conditions = (array)config('wp/route-conditions');
    }

    public function is($args, $action)
    {
        $this->addRoute(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $args, $action);
    }

    public function get($args, $action)
    {
        $this->addRoute(['GET'], $args, $action);
    }

    public function post($args, $action)
    {
        $this->addRoute(['POST'], $args, $action);
    }

    public function put($args, $action)
    {
        $this->addRoute(['PUT'], $args, $action);
    }

    public function patch($args, $action)
    {
        $this->addRoute(['PATCH'], $args, $action);
    }

    public function delete($args, $action)
    {
        $this->addRoute(['DELETE'], $args, $action);
    }

    public function options($args, $action)
    {
        $this->addRoute(['OPTIONS'], $args, $action);
    }

    public function group($options, $callback)
    {
        // reset
        $this->middleware = [];
        if (isset($options['middleware'])) {
            $this->middleware = $options['middleware'];
        }
        $callback($this);
    }

    public function addRoute($method, $vars, $action)
    {
        if (!is_array($vars)) {
            $key = $vars;
            $vars = [$vars => []];
        } else {
            $key = serialize($vars);
        }

        foreach ($vars as $key => $args) {
            $callable = array_get($this->conditions, $key, "is_$key");

            if (!is_callable($callable)) {
                throw new \Exception("Do not callable $callable", 1);
            }

            if (empty($args)) {
                $args = [[]];
            }

            if (!is_array($args)) {
                $args = [$args];
            }

            if (!is_array($action)) {
                $action = [$action];
            }

            if (count($action) === 1) {
                $action = ['middleware' => $this->middleware] + $action;
            } else if (isset($action['middleware'])) {
                $middleware = is_array($action['middleware']) 
                    ? $action['middleware']
                    : [$action['middleware']];
                $action['middleware'] = array_merge($middleware, $this->middleware);
            }

            foreach ((array)$method as $verb) {
                foreach ($args as $arg) {
                    $this->routes[$verb][] = [
                        'action' => $action,
                        'callable' => $callable,
                        'args' => $arg,
                    ];
                }
            }
        }
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function dispatch($httpMethod, $uri)
    {
        if (!isset($this->routes[$httpMethod])) {
            return [Dispatcher::NOT_FOUND];
        }
        $routes = $this->routes[$httpMethod];
        foreach ($routes as $route) {
            $args = $route['args'];
            if (is_null($args)) {
                $args = [];
            }
            if (!is_array($args)) {
                $args = [$args];
            }
            if (call_user_func_array($route['callable'], $args)) {
                $vars = $this->getQueriedVars();
                return [Dispatcher::FOUND, $route['action'], $vars];
            }
        }
        return [Dispatcher::NOT_FOUND];
    }

    public function getQueriedVars()
    {
        $obj = get_queried_object();
        if ($obj instanceof \WP_Post) {
            $class = Post::getClassNameByType($obj->post_type, Post::class);
            $post = $class::find($obj->ID);
            return ['post' => $post];
        }
        if ($obj instanceof \WP_Term) {
            $class = Taxonomy::getClassNameByType($obj->taxonomy, Taxonomy::class);
            $term = $class::find($obj->term_id);
            return ['term' => $term];
        }
        if ($obj instanceof \WP_User) {
            $user = User::find($obj->ID);
            return ['user' => $user];
        }
        return [];
    }

}
