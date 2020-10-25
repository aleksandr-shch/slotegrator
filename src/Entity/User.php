<?php


namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * App\Entity\User.
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $password;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private ?string $salt;

    /**
     * @var int
     * @ORM\Column(type="integer", length=20)
     */
    private int $money = 0;

    /**
     * @var int
     * @ORM\Column(type="integer", length=20)
     */
    private int $loyaltyPoint = 0;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private string $prize = '';

    /**
     * @var bool|int
     * @ORM\Column(type="boolean")
     */
    private bool $isSend = false;

    /**
     * @return int|null
     * @ORM\Column(name="is_send", options={"default": false})
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @return int
     */
    public function getMoney(): int
    {
        return $this->money;
    }

    /**
     * @param int $money
     */
    public function setMoney(int $money): void
    {
        $this->money = $money;
    }

    /**
     * @return int
     */
    public function getLoyaltyPoint(): int
    {
        return $this->loyaltyPoint;
    }

    /**
     * @param int $loyaltyPoint
     */
    public function setLoyaltyPoint(int $loyaltyPoint): void
    {
        $this->loyaltyPoint = $loyaltyPoint;
    }

    /**
     * @return string
     */
    public function getPrize(): string
    {
        return $this->prize;
    }

    /**
     * @param string $prize
     */
    public function setPrize(string $prize): void
    {
        $this->prize = $prize;
    }

    /**
     * @return bool|int
     */
    public function getIsSend()
    {
        return $this->isSend;
    }

    /**
     * @param bool|int $isSend
     */
    public function setIsSend($isSend): void
    {
        $this->isSend = $isSend;
    }
}