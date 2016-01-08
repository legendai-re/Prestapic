<?php

namespace PP\ReportBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ReportReason
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PP\ReportBundle\Entity\ReportReasonRepository")
 */
class ReportReason
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", nullable=true)
     */
    private $details;

    /**    
    * @ORM\OneToMany(targetEntity="PP\ReportBundle\Entity\ReportTicket", mappedBy="reason")
    * @Assert\Valid()
    */
    private $reportTickets;
    
    /**    
    * @ORM\OneToMany(targetEntity="PP\ReportBundle\Entity\DisableTicket", mappedBy="reason")
    * @Assert\Valid()
    */
    private $disabledTickets;
    
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
     * Set name
     *
     * @param string $name
     *
     * @return ReportReason
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set details
     *
     * @param string $details
     *
     * @return ReportReason
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reportTickets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reportTicket
     *
     * @param \PP\ReportBundle\Entity\ReportTicket $reportTicket
     *
     * @return ReportReason
     */
    public function addReportTicket(\PP\ReportBundle\Entity\ReportTicket $reportTicket)
    {
        $this->reportTickets[] = $reportTicket;

        return $this;
    }

    /**
     * Remove reportTicket
     *
     * @param \PP\ReportBundle\Entity\ReportTicket $reportTicket
     */
    public function removeReportTicket(\PP\ReportBundle\Entity\ReportTicket $reportTicket)
    {
        $this->reportTickets->removeElement($reportTicket);
    }

    /**
     * Get reportTickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReportTickets()
    {
        return $this->reportTickets;
    }

    /**
     * Add disableTicket
     *
     * @param \PP\ReportBundle\Entity\DisableTicket $disableTicket
     *
     * @return ReportReason
     */
    public function addDisableTicket(\PP\ReportBundle\Entity\DisableTicket $disableTicket)
    {
        $this->disableTickets[] = $disableTicket;

        return $this;
    }

    /**
     * Remove disableTicket
     *
     * @param \PP\ReportBundle\Entity\DisableTicket $disableTicket
     */
    public function removeDisableTicket(\PP\ReportBundle\Entity\DisableTicket $disableTicket)
    {
        $this->disableTickets->removeElement($disableTicket);
    }

    /**
     * Get disableTickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDisableTickets()
    {
        return $this->disableTickets;
    }
}
