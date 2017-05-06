<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relationship
 *
 * @ORM\Table(name="relationship", indexes={@ORM\Index(name="user_one_id_key", columns={"user_one_id"}), @ORM\Index(name="user_two_id_key", columns={"user_two_id"}), @ORM\Index(name="action_user_id_key", columns={"action_user_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\RelationshipRepository")
 */
class Relationship
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $status = 0;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="action_user_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $actionUser;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_one_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $userOne;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_two_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $userTwo;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Relationship
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set actionUser
     *
     * @param \Application\Entity\User $actionUser
     *
     * @return Relationship
     */
    public function setActionUser(\Application\Entity\User $actionUser = null)
    {
        $this->actionUser = $actionUser;

        return $this;
    }

    /**
     * Get actionUser
     *
     * @return \Application\Entity\User
     */
    public function getActionUser()
    {
        return $this->actionUser;
    }

    /**
     * Set userOne
     *
     * @param \Application\Entity\User $userOne
     *
     * @return Relationship
     */
    public function setUserOne(\Application\Entity\User $userOne = null)
    {
        $this->userOne = $userOne;

        return $this;
    }

    /**
     * Get userOne
     *
     * @return \Application\Entity\User
     */
    public function getUserOne()
    {
        return $this->userOne;
    }

    /**
     * Set userTwo
     *
     * @param \Application\Entity\User $userTwo
     *
     * @return Relationship
     */
    public function setUserTwo(\Application\Entity\User $userTwo = null)
    {
        $this->userTwo = $userTwo;

        return $this;
    }

    /**
     * Get userTwo
     *
     * @return \Application\Entity\User
     */
    public function getUserTwo()
    {
        return $this->userTwo;
    }
}

