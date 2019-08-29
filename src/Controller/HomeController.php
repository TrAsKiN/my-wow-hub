<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session)
    {
        if ($session->has('battletag')) {
            return $this->redirectToRoute('characters');
        }

        return $this->render('home/index.html.twig', [
            'url' => 'https://'. $_ENV['REGION'] .'.battle.net/oauth/authorize?'. http_build_query([
                'redirect_uri'  => $this->generateUrl('signin', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'client_id'     => $_ENV['CLIENT_ID'],
                'scope'         => 'wow.profile',
                'response_type' => 'code',
            ])
        ]);
    }

    /**
     * @Route("/signin", name="signin")
     */
    public function signin(Request $request, SessionInterface $session)
    {
        $httpClient = HttpClient::create();

        $authorizationResponse = $httpClient->request('POST', 'https://'. $_ENV['REGION'] .'.battle.net/oauth/token', [
            'auth_basic'    => [
                $_ENV['CLIENT_ID'],
                $_ENV['CLIENT_SECRET']
            ],
            'body' => [
                'redirect_uri'  => $this->generateUrl('signin', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'grant_type'    => 'authorization_code',
                'code'          => $request->query->get('code'),
            ],
        ]);

        $accessToken = json_decode($authorizationResponse->getContent())->access_token;

        $session->set('access_token', $accessToken);

        $userInfoResponse = $httpClient->request('GET', 'https://'. $_ENV['REGION'] .'.battle.net/oauth/userinfo', [
            'auth_bearer'   => $accessToken,
            'query' => [
                'access_token' => $accessToken,
            ],
        ]);

        $session->set('battletag', json_decode($userInfoResponse->getContent())->battletag);

        return $this->redirectToRoute('characters');
    }

    /**
     * @Route("/characters", name="characters")
     */
    public function characters(SessionInterface $session)
    {
        $httpClient = HttpClient::create();

        $charactersResponse = $httpClient->request('GET', 'https://'. $_ENV['REGION'] .'.api.blizzard.com/wow/user/characters', [
            'auth_bearer'   => $session->get('access_token'),
            'query' => [
                'access_token' => $session->get('access_token'),
            ],
        ]);

        return $this->render('characters/list.html.twig', [
            'characters' => json_decode($charactersResponse->getContent())->characters,
        ]);
    }
}
