<?php

namespace Api\Entity;

use Doctrine\ORM\EntityRepository;

class EmissionRepository extends EntityRepository
{
	public function findAllBySector($year)
	{
		return $this->createQueryBuilder()
			->from()
			->join()
			->where()
			->groupBy()
			->setParameter()
			->getResult(':year', $year);
	}
}