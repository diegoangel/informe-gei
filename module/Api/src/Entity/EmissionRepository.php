<?php

namespace Api\Entity;

use Doctrine\ORM\EntityRepository;

class EmissionRepository extends EntityRepository
{
    public function findBySector($year)
    {
        $dql = 'SELECT s.name, 
                s.color, 
                s.description,  
                SUM(e.value) as total
            FROM Api\Entity\Emission e 
            INNER JOIN e.sector s
            WHERE e.year = :year
            GROUP BY e.sector';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('year', $year)
            ->getResult();
    }

    public function findBySectorAndActivity($year, $sector)
    {
        $dql = 'SELECT a.id as activity, 
                a.name, 
                SUM(e.value) as total
            FROM Api\Entity\Emission e 
            INNER JOIN e.sector s
            INNER JOIN e.activity a
            WHERE e.value > 0
            AND e.sector = :sector
            AND e.year = :year
            GROUP BY e.activity
            ORDER BY a.name';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('year', $year)
            ->setParameter('sector', $sector)
            ->getResult();
    }

    public function findBySubactivity($year, $sector, $activity)
    {
        $dql = 'SELECT sa.id as subactivity, 
                sa.name, 
                SUM(e.value) as total
            FROM Api\Entity\Emission e 
            INNER JOIN e.subactivity sa
            WHERE e.value > 0
            AND e.sector = :sector
            AND e.activity = :activity
            AND e.year = :year
            GROUP BY e.subactivity
            ORDER BY sa.name';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('year', $year)
            ->setParameter('sector', $sector)
            ->setParameter('activity', $activity)
            ->getResult();
    }

    public function findByCategory($year, $sector, $activity, $subactivity)
    {
        $dql = 'SELECT c.id as category, 
                c.name,
                SUM(e.value) as total
            FROM Api\Entity\Emission e 
            INNER JOIN e.category c
            WHERE e.value > 0
            AND e.sector = :sector
            AND e.activity = :activity
            AND e.subactivity = :subactivity
            AND e.year = :year
            GROUP BY e.category
            ORDER BY c.name';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('year', $year)
            ->setParameter('sector', $sector)
            ->setParameter('activity', $activity)
            ->setParameter('subactivity', $subactivity)
            ->getResult();
    }

    public function findByGases($year)
    {
        $dql = 'SELECT g.id as gas, 
                g.name, 
                g.color, 
                SUM(e.value) as total
            FROM Api\Entity\Emission e 
            LEFT JOIN e.gas g 
            where e.year = :year GROUP BY e.gas ORDER BY total DESC';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('year', $year)
            ->getResult();
    }

    public function findByGasesAndSector($year)
    {
        $dql = 'SELECT s.name as sector, 
                g.name as gas, 
                sum(e.value) as total
            FROM Api\Entity\Emission e 
            LEFT JOIN e.gas g 
            LEFT JOIN e.sector s
            WHERE e.year = :year
            GROUP BY e.gas, e.sector
            ORDER BY total DESC';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('year', $year)
            ->getResult();
    }
}
