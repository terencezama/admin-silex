<?php

/**
 * Created by PhpStorm.
 * User: terence
 * Date: 17/01/2017
 * Time: 08:08
 */
namespace AKCMS\AKApi;
use AKCMS\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class Controller
{
    var $context = array();

    public function login(Request $request, Application $app)
    {
        $vars = json_decode($request->getContent(), true);

        try {
            if (empty($vars['_username']) || empty($vars['_password'])) {
                throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['_username']));
            }

            /**
             * @var $user User
             */
            $user = $app['users']->loadUserByUsername($vars['_username']);

            if (! $app['security.encoder.digest']->isPasswordValid($user->getPassword(), $vars['_password'], '')) {
                throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['_username']));
            } else {
                $response = [
                    'success' => true,
                    'token' => $app['security.jwt.encoder']->encode(['name' => $user->getUsername()]),
                ];
            }
        } catch (UsernameNotFoundException $e) {
            $response = [
                'success' => false,
                'error' => 'Invalid credentials',
            ];
        }

        return $app->json($response, ($response['success'] == true ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST));
    }

    public function test(Request $request, Application $app)
    {
        return $app->json(['hello' => 'world']);
    }
}