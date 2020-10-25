<?php


namespace App\Command;


use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SendMoneyToUser extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $emi;

    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $params;

    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @var string
     */
    protected static $defaultName = 'app:send:money';

    /**
     * SendMoneyToUser constructor.
     * @param EntityManagerInterface $emi
     * @param ParameterBagInterface $params
     */
    public function __construct(EntityManagerInterface $emi, ParameterBagInterface $params, UserService $userService)
    {
        $this->emi = $emi;
        $this->params = $params;
        $this->userService = $userService;
        parent::__construct(self::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Sending money to users');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->emi->getRepository(User::class)->findAll();
        $i = 1;
        $data = [];
        $total = count($users);
        $sent = 0;

        foreach($users as $user)
        {
            $money = $user->getMoney();
            $loyalPoints = $user->getLoyaltyPoint()*$this->params->get('app_coefficient');
            $moneyTotal = $money+$loyalPoints;
            $data[] = [$user->getId(), $moneyTotal];

            if($i % 10 == 0)
            {
                for($j = 1; $j<=10; $j++)
                {
                    if($success = $this->userService->sendingMoney($data[$j]))
                    {
                        $sent++;
                    }
                }
            }
            $i++;
        }

        if(!empty($data))
        {
            for($j = 1; $j<=count($data); $j++)
            {
                if($success = $this->userService->sendingMoney($data[$j]))
                {
                    $sent++;
                }
            }
        }
        $output->writeln('Total users: '.$total.' --- Sent: '.$sent);

        return Command::SUCCESS;
    }
}