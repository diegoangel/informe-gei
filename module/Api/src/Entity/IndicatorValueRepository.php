<?php

namespace Api\Entity;

use Doctrine\ORM\EntityRepository;

class IndicatorValueRepository extends EntityRepository
{
    public function getIndicatorValue($indicator)
    {
        $dql = 'SELECT iv FROM Api\Entity\IndicatorValue iv
            WHERE iv.indicator = :indicator
            ORDER BY iv.year ASC';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('indicator', $indicator)
            ->getArrayResult();
    }
}    

