<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class UserRepository extends EntityRepository
{
    /**
     * @param $user
     * @return mixed
     */
    public function getUserMoney($user)
    {
        $currentUser = $this->getEntityManager()->getRepository(User::class)->findOneBy(['id' => $user]);

        return $currentUser->getMoney();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getUserPrize($user)
    {
        $currentUser = $this->getEntityManager()->getRepository(User::class)->findOneBy(['id' => $user]);

        return $currentUser->getPrize();
    }

    /**
     * @param $user
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function setUserSend($user)
    {
        $currentUser = $this->getEntityManager()->getRepository(User::class)->findOneBy(['id' => $user]);
        $currentUser->setIsSend(true);
        $this->getEntityManager()->persist($currentUser);
        $this->getEntityManager()->flush();
    }
}