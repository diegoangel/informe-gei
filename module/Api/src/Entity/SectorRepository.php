<?php

namespace Api\Entity;

use Doctrine\ORM\EntityRepository;

class SectorRepository extends EntityRepository
{
    public function getSector($sector)
    {
        $dql = 'SELECT s FROM Api\Entity\Sector s WHERE s.id = :sector';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('sector', $sector)
            ->getArrayResult();
    }

    public function getSectorsNameOrderedyName()
    {
        $dql = 'SELECT s.name FROM Api\Entity\Sector s ORDER BY s.name';

        return $this->getEntityManager()->createQuery($dql)
            ->getArrayResult();
    }
}
