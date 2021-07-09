<?php

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;


return function (App $app) {
    $app->group('/api/v1', function (RouteCollectorProxy $group) {

            /** Grouping Member Endpoints */
            $group->group('/members', function (RouteCollectorProxy $group) {
                /** Get all Members */
                $group->get('', \MembersController::class.':getAll')->setName('getAll');

                /** Add a Member */
                $group->post('', \MembersController::class. ':add')->setName('add');

                /** Get a single Member */
                $group->get('/{memberId}', \MembersController::class. ':get')->setName('get');

                // /** Update a single Member */
                // $group->put('/{memberId}', \MembersController::class. ':update')->setName('update');

                // /** Update a single Member with partial data*/
                // $group->path('/{memberId}/extend-licence', \MembersController::class. ':extendLicense')->setName('extendMemberLicense');

                // /** Delete a single Member */
                // $group->delete('/{memberId}', \MembersController::class. ':delete')->setName('delete');
            });
    });
   
   $app->get('/public', function (Request $request, Response $response, $args) {
       $response->getBody()->write("Hello world!");
       return $response;
   });
   
   // Define app routes
   $app->get('/public/{name}', function (Request $request, Response $response, $args) {
       $name = $args['name'];
       $response->getBody()->write("Hello, $name");
       return $response;
   });
};