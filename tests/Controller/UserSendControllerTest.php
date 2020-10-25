<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserSendControllerTest extends WebTestCase
{
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $params;

    /**
     * CompanyController constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function testCompanyHistoricalDataGet()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            $this->params->get('app_get_user_send_url'),
            [
                'user' => 32,
                'amount' => 10000
            ]
        );
        $host = $this->params->get('app_get_user_send_x_rapidapi_host');
        $apiKey = $this->params->get('app_get_user_send_x_rapidapi_key');

        $client->setServerParameter('x-rapidapi-host', $host);
        $client->setServerParameter('x-rapidapi-key', $apiKey);
        $client->setServerParameter('useQueryString', true);
        $this->assertResponseStatusCodeSame(200, $client->getResponse()->getStatusCode());
    }
}