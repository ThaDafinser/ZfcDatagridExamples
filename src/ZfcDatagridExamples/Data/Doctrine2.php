<?php
namespace ZfcDatagridExamples\Data;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Input\StringInput;
use ZfcDatagridExamples\Entity\Person;
use ZfcDatagridExamples\Entity\Group;

class Doctrine2 implements ServiceLocatorAwareInterface
{

    private $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator            
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    private function createTables()
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_zfcDatagrid');
        
        /* @var $cli \Symfony\Component\Console\Application */
        $cli = $this->getServiceLocator()->get('doctrine.cli');
        $helperSet = $cli->getHelperSet();
        $helperSet->set(new EntityManagerHelper($em), 'em');
        
        $fp = tmpfile();
        
        $input = new StringInput('orm:schema-tool:create');
        
        /* @var $command \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand */
        $command = $cli->get('orm:schema-tool:create');
        $returnCode = $command->run($input, new StreamOutput($fp));
        
        $phpArray = $this->getServiceLocator()->get('zfcDatagrid.examples.data.phpArray');
        $persons = $phpArray->getPersons();
        
        $this->createData(new Person(), $persons);
        
        $person1 = $em->find('ZfcDatagridExamples\Entity\Person', 2);
        $person2 = $em->find('ZfcDatagridExamples\Entity\Person', 5);
        
        $group = new Group();
        $group->setPerson($person1);
        $group->setName('MyGroup');
        $em->persist($group);
        
        $group = new Group();
        $group->setPerson($person1);
        $group->setName('MyGroup2');
        $em->persist($group);
        
        $group = new Group();
        $group->setPerson($person2);
        $group->setName('MyGroup');
        $em->persist($group);
        $em->flush();
    }

    private function createData($entity, $data)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_zfcDatagrid');
        
        foreach ($data as $row) {
            
            $newEntity = clone $entity;
            foreach ($row as $key => $value) {
                $method = 'set' . ucfirst($key);
                $newEntity->{$method}($value);
            }
            
            $em->persist($newEntity);
        }
        
        $em->flush();
    }

    /**
     *
     * @return QueryBuilder
     */
    public function getPersons()
    {
        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_zfcDatagrid');
        $personRepo = $entityManager->getRepository('ZfcDatagridExamples\Entity\Person');
        
        // Test if the SqLite is ready...
        try {
            $data = $personRepo->find(2);
        } catch (\Exception $e) {
            $this->createTables();
            $data = $personRepo->find(2);
        }
        
        $qb = $entityManager->createQueryBuilder();
        $qb->select('p');
        $qb->from('ZfcDatagridExamples\Entity\Person', 'p');
        
        return $qb;
    }
}
