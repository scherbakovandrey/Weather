<?php

namespace App\Repository;

use App\Entity\Country;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CountryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function getAll($page = 1)
    {
        $qb = $this->createQueryBuilder('p')->getQuery();

        $paginator = $this->paginate($qb, $page);

        $iterator = $paginator->getIterator();

        $countries = [];
        foreach ($iterator as $country)
        {
            $countries[] = [
                'id' => $country->getId(),
                'country_code' => $country->getCode(),
                'country_name' => $country->getName(),
            ];
        }

        return $countries;
    }

    public function getCountryInfoIfExists(string $country)
    {
        $countryEntity = $this->findByNameField($country);
        return $countryEntity ? [
            'country_id' => $countryEntity->getId(),
            'country_code' => $countryEntity->getCode(),
            'country_name' => $country,
        ] : false;
    }

    private function findByNameField(string $country)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name = :val')
            ->setParameter('val', $country)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
