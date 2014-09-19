<?php
namespace ZfcDatagridExamples\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="personGroup")
 */
class Group
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="ZfcDatagridExamples\Entity\Person")
     * @ORM\JoinColumn(name="personId", referencedColumnName="id", nullable=false)
     *
     * @var Person
     */
    protected $person;

    /**
     *
     * @param integer $id            
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param string $name            
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPerson(Person $person)
    {
        $this->person = $person;
    }

    public function getPerson()
    {
        return $this->person;
    }
}
