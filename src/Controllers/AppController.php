<?php

namespace App\Controllers;

class AppController
{
    protected array|object|string $response;
    protected int $code = 200;
    protected string $content_type = 'application/json';
    public function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            call_user_func_array([$this, $method], $args);
            $this->ShowResponse();
        } else {
            throw new \BadMethodCallException("Method $method does not exist");
        }
    }
    public static function headers(int $code, string $content_type = 'text/html'): void
    {
        header("Content-Type: $content_type; charset=utf-8", true, $code);
    }
    protected function Jsonlistener(): array|null
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    protected function getlistener(): array|null
    {
        return $_GET;
    }
    protected function requestlistener(): array|null
    {
        return $_REQUEST;
    }
    public static function staticMethod()
    {
        echo 'Static method invoked';
    }
    public function NotFound404(string $CurrentUri): void
    {
        $this->code = 404;
        $this->content_type = 'application/json';
        $this->response = ['status' => false, 'message' => 'page not found'];
        self::headers($this->code, $this->content_type);
        exit(json_encode_unicode($this->response));
    }
    protected function OnErrorPage(string|array|object $message, int $code): void
    {
        $this->code = $code;
        $this->content_type = 'application/json';
        $this->response = ['status' => false, 'message' => $message];
        $this->ShowResponse();
    }
    protected function ShowResponse(): void
    {
        self::headers($this->code, $this->content_type);
        exit(json_encode_unicode($this->response));
    }
    protected function ShowResponseHtml(): void
    {
        $this->content_type = "text/html";
        self::headers($this->code, $this->content_type);
        exit($this->response);
    }
    protected function CheckObject(array $array, array $checkarr): bool
    {
        $CheckObject = true;
        foreach ($checkarr as $indexChecker) {
            if (!isset($array[$indexChecker])) {
                $CheckObject = false;
                break;
            }
        }
        return $CheckObject;
    }
}
