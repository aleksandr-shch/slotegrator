<?php


namespace App\Service;


use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserService
{
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $params;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    private UserRepository $userRepository;

    /**
     * UserService constructor.
     * @param ParameterBagInterface $params
     * @param HttpClientInterface $client
     * @param UserRepository $userRepository
     */
    public function __construct(ParameterBagInterface $params, HttpClientInterface $client, UserRepository $userRepository)
    {
        $this->params = $params;
        $this->client = $client;
        $this->userRepository = $userRepository;
    }

    /**
     * @param $user
     * @param MailerInterface $mailer
     * @throws TransportExceptionInterface
     */
    public function notifyUser($user, MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('noreply@example.com')
            ->to($user->getEmail())
            ->subject('You are registered')
            ->html('<p>'.$user->getEmail().'---'.$user->getFirstName().'---'.$user->getPlainPassword().'</p>');

        $mailer->send($email);
    }

    /**
     * @param $data
     * @return bool
     * @throws ClientExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function sendingMoney($data): bool
    {
        $url = $this->params->get('app_get_user_send_url');
        $host = $this->params->get('app_get_user_send_x_rapidapi_host');
        $apiKey = $this->params->get('app_get_user_send_x_rapidapi_key');
        $response = $this->client->request(
            'GET',
            $url, [
            'headers' => [
                'x-rapidapi-host' => $host,
                'x-rapidapi-key' => $apiKey,
                'useQueryString' => true
            ],
            'query' => [
                'user' => $data[0],
                'amount' => $data[1]
            ],
        ]);

        if($response->getStatusCode() === 200)
        {
            if(!empty($response->getContent()))
            {
                $this->userRepository->setUserSend($data[0]);

                return true;
            }
        }
        else
        {
            return false;
        }

        return false;
    }
}