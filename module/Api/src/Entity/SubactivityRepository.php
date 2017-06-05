<?php

namespace Api\Entity;

use Doctrine\ORM\EntityRepository;

class SubactivityRepository extends EntityRepository
{
	public function findActivitySectorBySector($sector)
	{
		$dql = 'SELECT sub.name
            FROM Api\Entity\Subactivity sub
            INNER JOIN sub.activity a 
            INNER JOIN Api\Entity\Sector s WITH s.id = a.sector
            WHERE s.id = :sector
            ORDER BY sub.name';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('sector', $sector)
            ->getResult();       	
	}
}
