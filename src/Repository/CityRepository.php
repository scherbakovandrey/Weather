<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function getCityInfoIfExists(string $city)
    {
        $cityEntity = $this->findByNameField($city);
        return $cityEntity ? [
            'city_id' => $cityEntity->getId(),
            'city_name' => $city,
            'country_code' => $cityEntity->getCountry()->getCode()
        ] : false;
    }

    public function getCitiesInCountryInfo(int $country_id, string $country_code)
    {
        $cityEntities = $this->findByCountryIdNameField($country_id);

        $info = [];
        foreach ($cityEntities as $cityEntity)
        {
            $info[] = [
                'city_id' => $cityEntity->getId(),
                'city_name' => $cityEntity->getName(),
                'country_code' => $country_code,
            ];
        }
        return $info;
    }

    public function getAllCitiesInfo()
    {
        $qbResult = $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult()
        ;

        $info = [];
        foreach ($qbResult as $cityEntity)
        {
            $info[] = [
                'city_id' => $cityEntity->getId(),
                'city_name' => $cityEntity->getName(),
                'country_code' => $cityEntity->getCountry()->getCode()
            ];
        }
        return $info;
    }

    private function findByNameField(string $city)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name = :val')
            ->setParameter('val', $city)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    private function findByCountryIdNameField(string $country_id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.country = :val')
            ->setParameter('val', $country_id)
            ->getQuery()
            ->getResult()
        ;
    }
}
