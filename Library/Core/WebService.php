<?php

namespace Library\Core;

/**
 * Description of WebService
 *
 * @author Administrateur
 */
class WebService {

    public static function getDataRequest() {
            $method = strtolower($_SERVER['REQUEST_METHOD']);
            switch ($method) {
                case 'get':
                case 'delete':
                    $data = WebService::getDataFromGET();
                    break;
                case 'post':
                    $data = WebService::getDataFromPOST();
                    break;
                case 'put':
                    $data = WebService::getDataFromPUT();
                    break;
                default:
                    break;
            }
            return ['method' => $method, 'data' => $data];
    }

    public static function getDataFromGET() {
        $data = $_GET;
        unset($data['url']);
        return $data;
    }

    public static function getDataFromPOST() {
        return $_POST;
    }
    
    public static function getDataFromPUT() {
        $raw_data = file_get_contents('php://input');
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));
        $data = array();
        
        if ($boundary) {
            $parts = array_slice(explode($boundary, $raw_data), 1);
            foreach ($parts as $part) {
                if ($part == "--\r\n")
                break;
                $part = ltrim($part, "\r\n");
                list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);
                $raw_headers = explode("\r\n", $raw_headers);
                $headers = array();
                foreach ($raw_headers as $header) {
                    list($name, $value) = explode(':', $header);
                    $headers[strtolower($name)] = ltrim($value, ' ');
                }
                if (isset($headers['content-disposition'])) {
                    $filename = null;
                    preg_match('/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/', $headers['content-disposition'], $matches);
                    list(, $type, $name) = $matches;
                    isset($matches[4]) and $filename = $matches[4];
                    switch ($name) {
                        case 'userfile':
                        file_put_contents($filename, $body);
                        break;
                        default:
                        $data[$name] = substr($body, 0, strlen($body) - 2);
                        break;
                    }
                }
            }
        }

        return $data;
    }
    
    public static function sendResponse($data, $status = 200){
        header("Content-type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Expose-Headers: *");
        header("Access-Control-Allow-Allow-Methods: GET, PUT, POST, DELETE");
        header("Access-Control-Allow-Credentials: true");
        
        http_response_code($status);
        
        echo json_encode($data, JSON_PRETTY_PRINT);

    }

}
