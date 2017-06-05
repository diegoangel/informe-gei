<?php

namespace Api\Entity;

use Doctrine\ORM\EntityRepository;

class IndicatorRepository extends EntityRepository
{
    public function getIndicator($indicator)
    {
        $dql = 'SELECT i FROM Api\Entity\Indicator i WHERE i.id = :indicator';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('indicator', $indicator)
            ->getArrayResult();
    }
}
