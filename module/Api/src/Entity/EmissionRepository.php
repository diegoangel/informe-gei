<?php

namespace Api\Entity;

use Doctrine\ORM\EntityRepository;

class EmissionRepository extends EntityRepository
{
    public function findSectorByYear($year)
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

    public function findActivityByYearAndSector($year, $sector)
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

    public function findSubactivityByYearSectorAndActivity($year, $sector, $activity)
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

    public function findCategoryByYearSectorActivityAndSubactivity($year, $sector, $activity, $subactivity)
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

    public function findGasesByYear($year)
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

    public function findGasesAndSectorByYear($year)
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

    public function findSectorGroupedByYear()
    {
        $dql = 'SELECT s.name as sector, 
                e.year, 
                SUM(e.value) as total
            FROM Api\Entity\Emission e 
            LEFT JOIN e.sector s
            GROUP BY e.year, s.name';

        return $this->getEntityManager()->createQuery($dql)
            ->getResult();            
    }

    public function findSubactivitySectorBySector($sector)
    {
        $dql = 'SELECT sub.name as sector, 
                e.year, 
                SUM(e.value) as total
            FROM Api\Entity\Emission e
            INNER JOIN e.subactivity sub
            INNER JOIN e.sector s 
            WHERE s.id = :sector 
            GROUP BY e.year, sub.name';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('sector', $sector)
            ->getResult();            
    }

    public function findSubactivitySectorCategoryBySectorSubactivity($sector, $subactivity)
    {
        $dql = 'SELECT DISTINCT c.name
            FROM Api\Entity\Emission e
            INNER JOIN e.subactivity sub
            INNER JOIN e.sector s 
            INNER JOIN e.category c 
            WHERE s.id = :sector
            AND sub.id = :subactivity
            ORDER BY c.name';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('sector', $sector)
            ->setParameter('subactivity', $subactivity)
            ->getResult();
    }

    public function findSubactivitySectorCategoryBySectorSubactivityGroupByYearName($sector, $subactivity)
    {
        $dql = 'SELECT sub.name as subcategoria, 
                e.year, 
                c.name, 
                SUM(e.value) as value
            FROM Api\Entity\Emission e
            INNER JOIN e.subactivity sub
            INNER JOIN e.sector s 
            INNER JOIN e.category c 
            WHERE s.id = :sector
            AND sub.id = :subactivity
            GROUP BY e.year, c.name';

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('sector', $sector)
            ->setParameter('subactivity', $subactivity)
            ->getResult();            
    }
}
