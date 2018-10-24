<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Permissions\Acl\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity;
use Doctrine\Common\Collections;

/**
 * UserRole
 *
 * @ORM\Table(name="user_role", indexes={@ORM\Index(name="parent_id", columns={"parent_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\UserRole")
 */
class UserRole extends Entity\AbstractEntity implements RoleInterface
{
    use Entity\Property\Id;

    /**
     * Guest role id
     */
    const GUEST = 1;

    /**
     * User role id
     */
    const USER = 2;

    /**
     * Operator role id
     */
    const OPERATOR = 3;

    /**
     * Cook role id
     */
    const COOK = 4;

    /**
     * Driver role id
     */
    const DRIVER = 5;

    /**
     * Admin role id
     */
    const ADMIN = 6;

    /**
     * @var string
     *
     * @ORM\Column(name="role_id", type="string", length=255)
     */
    private $roleId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var UserRole
     *
     * @ORM\ManyToOne(targetEntity="UserRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     *
     */
    private $users;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @param string $roleId
     *
     * @return $this
     */
    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param UserRole $parent
     *
     * @return $this
     */
    public function setParent(UserRole $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return UserRole
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * @return Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
