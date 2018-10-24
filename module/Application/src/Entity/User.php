<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections;
use App\Entity;
use DateTime;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="user_email", columns={"email"})})
 * @ORM\Entity(repositoryClass="Application\Repository\User")
 * @ORM\EntityListeners({"Application\Entity\Listener\User"})
 */
class User extends Entity\AbstractEntity implements Property\CreatorInterface, Property\ChangerInterface
{
    use Entity\Property\Id;
    use Property\Creator;
    use Entity\Property\CreatedAt;
    use Property\Changer;
    use Entity\Property\ChangedAt;

    /**
     * @var string
     */
    const STATUS_NOT_VERIFIED = 0;

    /**
     * @var string
     */
    const STATUS_VERIFIED = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status = self::STATUS_NOT_VERIFIED;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=255)
     */
    protected $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @var Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="UserRole", inversedBy="user")
     * @ORM\JoinTable(name="user_role_ref",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    protected $roles;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="password_changed_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"password"})
     */
    protected $passwordChangedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="password_change_session_id", type="string", length=40, nullable=true, options={"fixed"=true})
     */
    protected $passwordChangeSessionId;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = new Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param Collections\Collection $roles
     *
     * @return $this
     */
    public function setRoles(Collections\Collection $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoleList()
    {
        $roleList = [];
        if ($this->roles instanceof \Traversable) {
            foreach ($this->roles as $role) {
                $roleList[$role->getId()] = $role->getRoleId();
            }
        }

        return $roleList;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return bool
     */
    public function isCoworker()
    {
        foreach ([UserRole::OPERATOR, UserRole::COOK, UserRole::DRIVER, UserRole::ADMIN] as $coworker) {
            if (in_array($coworker, array_keys($this->getRoleList()))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return DateTime
     */
    public function getPasswordChangedAt()
    {
        return $this->passwordChangedAt;
    }

    /**
     * @param DateTime $passChangedAt|null
     *
     * @return $this
     */
    public function setPasswordChangedAt(DateTime $passChangedAt = null)
    {
        if ($passChangedAt) {
            $this->passwordChangedAt = $passChangedAt;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPasswordChangeSessionId()
    {
        return $this->passwordChangeSessionId;
    }

    /**
     * @param string $sessionId
     *
     * @return $this
     */
    public function setPasswordChangeSessionId($sessionId = null)
    {
        $this->passwordChangeSessionId = $sessionId;

        return $this;
    }

    /**
     * User full name
     *
     * @return string
     */
    public function getName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @param \Application\Entity\UserRole $role
     *
     * @return $this
     */
    public function removeRole(UserRole $role)
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @param Collections\Collection|null $roles
     *
     * @return $this
     */
    public function addRoles(Collections\Collection $roles = null)
    {
        if (!$roles) {
            return $this;
        }

        foreach ($roles as $role) {
            $this->roles->add($role);
        }

        return $this;
    }

    /**
     * @param Collections\Collection|null $roles
     *
     * @return $this
     */
    public function removeRoles(Collections\Collection $roles = null)
    {
        if (!$roles) {
            return $this;
        }

        foreach ($roles as $role) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    /**
     * Create user secret
     *
     * @param bool $force
     *
     * @return $this
     */
    public function createSecret($force = false)
    {
        if ($force || !$this->getSecret()) {
            $salt = $this->getEmail() . rand(1, 10000);
            $this->setSecret(md5(time() . 'random-salt-' . $salt));
        }

        return $this;
    }

    /**
     * Regenerate secret
     *
     * @return $this
     */
    public function regenerateSecret()
    {
        $this->createSecret(true);

        return $this;
    }
}
