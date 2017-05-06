<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatusLike
 *
 * @ORM\Table(name="status_like", indexes={@ORM\Index(name="status_like_user_id_key", columns={"user_id"}), @ORM\Index(name="status_like_status_id_key", columns={"status_id"})})
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\StatusLikeRepository")
 */
class StatusLike
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
     * @var \Application\Entity\Status
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Status")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $status;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $user;


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
     * @param \Application\Entity\Status $status
     *
     * @return StatusLike
     */
    public function setStatus(\Application\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Application\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\User $user
     *
     * @return StatusLike
     */
    public function setUser(\Application\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

