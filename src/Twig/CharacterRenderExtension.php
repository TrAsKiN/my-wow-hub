<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CharacterRenderExtension extends AbstractExtension
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('character_render', [$this, 'getCharacterMedia']),
        ];
    }

    public function getCharacterMedia($name, $realm, $raceId, $genderType)
    {
        $genderId = ($genderType == 'MALE' ? '0' : '1');

        $httpClient = HttpClient::create();

        $characterMediaResponse = $httpClient->request('GET', 'https://'. $_ENV['REGION'] .'.api.blizzard.com/profile/wow/character/'. $realm .'/'. $name .'/character-media', [
            'auth_bearer'   => $this->session->get('access_token'),
            'headers' => [
                'Battlenet-Namespace' => 'profile-'. $_ENV['REGION']
            ],
            'query' => [
                'locale'        => $_ENV['LOCALE'],
                'region'        => $_ENV['REGION']
            ],
        ]);

        if ($characterMediaResponse->getStatusCode() != 200) return 'https://render-'. $_ENV['REGION'] .'.worldofwarcraft.com/character/'. $name .'/00/000000000-avatar.jpg?alt=/shadow/avatar/'. $raceId .'-'. $genderId .'.jpg';

        $characterMedia = json_decode($characterMediaResponse->getContent());

        return $characterMedia->avatar_url .'?alt=/shadow/avatar/'. $raceId .'-'. $genderId .'.jpg';
    }
}
