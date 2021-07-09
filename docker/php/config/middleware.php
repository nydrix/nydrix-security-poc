<?php

use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

return function (App $app) {

    $options = [
        "header" => "Authorization",
        'algorithm' => 'RS256',
        "regexp" => "/Bearer\s+(.*)$/i",
        "cookie" => "token",
        "attribute" => "token",
        "idtokenAttribute" => "idtoken",
        "secret" =>  "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoaLLT9hkcSj2tGfZsjbu
7Xz1Krs0qEicXPmEsJKOBQHauZ/kRM1HdEkgOJbUznUspE6xOuOSXjlzErqBxXAu
4SCvcvVOCYG2v9G3+uIrLF5dstD0sYHBo1VomtKxzF90Vslrkn6rNQgUGIWgvuQT
xm1uRklYFPEcTIRw0LnYknzJ06GC9ljKR617wABVrZNkBuDgQKj37qcyxoaxIGdx
EcmVFZXJyrxDgdXh9owRmZn6LIJlGjZ9m59emfuwnBnsIQG7DirJwe9SXrLXnexR
QWqyzCdkYaOqkpKrsjuxUj2+MHX31FqsdpJJsOAvYXGOYBKJRjhGrGdONVrZdUdT
BQIDAQAB
-----END PUBLIC KEY-----"
    ];

    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "secure" => false, // only in dev mode
        "path" => "/api",
        "ignore" => "/api/public",
        "header" => $options["header"],
        "secret" => $options["secret"], //"B~UThNwUQ6-Ck2GxKwgavQ08q~965~9a_3",
        "regexp" => $options["regexp"],
        "attribute" => $options["attribute"],
        "cookie" => $options["cookie"],
        'algorithm' => $options["algorithm"],
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];
            return $response
                ->withHeader("Content-Type", "application/json")
                ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    ]));

    /**
     * Add Error Middleware
     *
     * @param bool                  $displayErrorDetails -> Should be set to false in production
     * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
     * @param bool                  $logErrorDetails -> Display error details in error log
     * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
     *
     * Note: This middleware should be added last. It will not handle any exceptions/errors
     * for middleware added after it.
     */
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    // Parse json, form data and xml
    $app->addBodyParsingMiddleware();

    $app->add(function ($request, $handler) {
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $response = $handler->handle($request);
        $response = $response
                ->withHeader('Access-Control-Allow-Origin', 'http://localhost')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');

        // Optional: Allow Ajax CORS requests with Authorization header
        $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');  
        
        return $response;
    });

    $app->add(function (Request $request, RequestHandler $handler) {

        // // get roles from bearer token
        // $token;
        // $header = $request->getHeaderLine($options["header"]);

        // if (false === empty($header)) {
        //     if (preg_match($options["regexp"], $header, $matches)) {
        //         $token =  $matches[1];
        //     }
        // } else {
        //     /* Token not found in header try a cookie. */
        //     $cookieParams = $request->getCookieParams();

        //     if (isset($cookieParams[$options["cookie"]])) {
        //         if (preg_match($options["regexp"], $cookieParams[$options["cookie"]], $matches)) {
        //             $token =  $matches[1];
        //         } else {
        //             $token =  $cookieParams[$options["cookie"]];
        //         }
        //     };
        //     /* If everything fails log and throw. */
        //     //no token
        // }

        // if (false === empty($token)) {
        //     $decoded = JWT::decode(
        //         $token,
        //         $options["secret"],
        //         (array) $options["algorithm"]
        //     );

        //      /* Add decoded token to request as attribute when requested. */
        //     if ($options["idtokenAttribute"]) {
        //         $request = $request->withAttribute($options["idtokenAttribute"], $decoded);
        //     }
        // }
       

        // todo : add code to load the user club acesses
        $request = $request->withAttribute('clubs', ['4066', '4000']);
        // this can be added to a session so you don't have to feth it everytime

        return $handler->handle($request);
     });
     

    // Add the Slim built-in routing middleware
    $app->addRoutingMiddleware();

    // Catch exceptions and errors
    $app->add($errorMiddleware);
};
