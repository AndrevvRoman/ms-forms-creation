<?php
/**
 * Created by PhpStorm.
 * User: vmv
 * Date: 20.06.2018
 * Time: 18:12
 */

namespace App\Security;

use Monolog\Logger;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TokenAuthenticator extends AbstractAuthenticator
{
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request) : bool
    {
        return $request->headers->has('YT-AUTH-TOKEN');
    }


    public function authenticate(Request $request): PassportInterface
    {
        $token = $request->headers->get('YT-AUTH-TOKEN');
        if (null === $token) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('API token is required');
        }
        return new SelfValidatingPassport(
            new UserBadge($token, function ($apiKey) {
                $url = $_ENV['APP_ENV'] === 'prod' ? 'nginx_user_cp' : 'https://back.yourtar.ru';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url . "/api/user/");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'YT-AUTH-TOKEN: ' . $apiKey,
                ));
                $data = json_decode(curl_exec($ch));
                curl_close($ch);

                if (!isset($data->user)) {
                    throw new UserNotFoundException('Invalid credentials');
                }

                $user = new User($data->user->id, $data->user->email, $data->user->roles);

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $firewallName) : ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) : Response
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

}
