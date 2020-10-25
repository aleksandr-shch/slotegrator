<?php


namespace App\Service;


use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PrizeService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $params;

    /**
     * PrizeService constructor.
     * @param UserRepository $userRepository
     * @param ParameterBagInterface $params
     */
    public function __construct(UserRepository $userRepository, ParameterBagInterface $params)
    {
        $this->userRepository = $userRepository;
        $this->params = $params;
    }

    /**
     * @param $user
     * @return string
     */
    public function getPrize($user): string
    {
        $prizeType = array_rand(['money', 'loyaltyPoint', 'prize'], 1);

        if($prizeType[0] == 'money')
        {
            $moneyAmount = $this->userRepository->getUserMoney($user);
            $max = $this->params->get('app_app_money_total_amount') - $moneyAmount;
            $amount = rand(0, $max);
            $type = 'money';

            if(empty($max))
            {
                $type = 'loyalty points';
                $amount = rand(0, 100);
            }
            $message = 'You earn '.$amount.' '.$type;
        }
        elseif($prizeType[0] == 'loyaltyPoint')
        {
            $message = 'You earn '.rand(0, 100).' loyalty points';
        }
        else
        {
            $prizes = $this->params->get('app_prizes');
            $userPrizes = $this->userRepository->getUserPrize($user);
            $type = 'prize';

            if($diff = array_diff($prizes, $userPrizes))
            {
                $amount = array_rand($diff, 1)[0];
            }
            else
            {
                $type = 'loyalty points';
                $amount = rand(0, 100);
            }
            $message = 'You earn '.$amount.' '.$type;
        }

        return $message;
    }
}