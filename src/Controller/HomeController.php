<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
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
    public function signin(Request $request)
    {
        $httpClient = new CurlHttpClient([
            'auth_basic'    => [$_ENV['CLIENT_ID'], $_ENV['CLIENT_SECRET']],
            'headers'       => ['Content-Type' => 'multipart/form-data'],
        ]);

        $response = $httpClient->request('POST', 'https://'. $_ENV['REGION'] .'.battle.net/oauth/token', [
            'body' => [
                'redirect_uri'  => $this->generateUrl('home', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'scope'         => 'wow.profile',
                'grant_type'    => 'authorization_code',
                'code'          => $request->query->get('code'),
            ],
        ]);

        return $this->redirectToRoute('home');
    }
}
