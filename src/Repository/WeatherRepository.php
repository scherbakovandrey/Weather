<?php

namespace App\Repository;

use App\Entity\Weather;
use Symfony\Bridge\Doctrine\RegistryInterface;

class WeatherRepository extends AbstractRepository
{
    // we may set this as the setter
    const ITEMS_PER_PAGE = 10;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    public function __construct(RegistryInterface $registry, CityRepository $cityRepository)
    {
        parent::__construct($registry, Weather::class);

        $this->cityRepository = $cityRepository;
    }

    // We assume that page and dates - are mandatory (or at least set by some defaults) here. Temp and direction - optional.
    public function getAll($page = 1, $start_date, $end_date, $temperature = 0, $direction = 'less', $city_id)
    {
        $qb = $this->createQueryBuilder('p')
                ->andWhere('p.date >= :datetime_start_val')
                ->andWhere('p.date <= :datetime_end_val')
                ->setParameter('datetime_start_val', $start_date)
                ->setParameter('datetime_end_val', $end_date);

        if (!empty($temperature) && !empty($direction) && in_array($direction, ['less', 'higher']))
        {
            if ($direction == 'less')
            {
                $qb->andWhere('p.temp <= :temp_val');
            } else {
                $qb->andWhere('p.temp >= :temp_val');
            }
            $qb->setParameter('temp_val', $temperature);
        }

        if (!empty($city_id) && $city_id > 0)
        {
            $qb
                ->andWhere('p.city = :city_val')
                ->setParameter('city_val', $city_id);
            ;

        }

        $qb->getQuery();

        $paginator = $this->paginate($qb, $page, self::ITEMS_PER_PAGE);

        $iterator = $paginator->getIterator();

        $weatherData = [];

        $i = 0;
        foreach ($iterator as $weather)
        {
            $index = (($page - 1) * self::ITEMS_PER_PAGE) + $i;
            $weatherData[$index] = [
                'date' => $weather->getDate()->format('Y-m-d'),
                'country' => $weather->getCity()->getCountry()->getName(),
                'city' => $weather->getCity()->getName(),
                'temperature' => $weather->getTemp(),
            ];
            $i++;
        }

        return $weatherData;
    }

    public function store(int $city_id, $weatherData)
    {
        // check if the record exists, if so - we update the record
        // otherwise we insert the record
        $datetime = $weatherData['datetime'];

        $recordId = $this->getIdIfRecordExists($city_id, $datetime);
        if ( $recordId ) {
            $this->updateRecord($recordId, $city_id, $weatherData);
        } else {
            $this->addNewRecord($city_id, $weatherData);
        }
    }

    private function addNewRecord($city_id, $weatherData)
    {
        $city = $this->cityRepository->find($city_id);
        $weather = new Weather();

        $weather->setCity($city);
        $weather->setDate(new \DateTime($weatherData['datetime']));
        $weather->setTemp($weatherData['temp']);

        $this->getEntityManager()->persist($weather);
        $this->getEntityManager()->flush();
    }

    private function updateRecord($recordId, $city_id, $weatherData)
    {
        $city = $this->cityRepository->find($city_id);
        $weather = $this->find($recordId);

        $weather->setCity($city);
        $weather->setDate(new \DateTime($weatherData['datetime']));
        $weather->setTemp($weatherData['temp']);

        $this->getEntityManager()->persist($weather);
        $this->getEntityManager()->flush();
    }

    private function getIdIfRecordExists($city_id, $datetime)
    {
        $entity = $this->findByCityAndDate($city_id, $datetime);
        return $entity ? $entity->getId() : false;
    }

    private function findByCityAndDate($city_id, $datetime)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.city = :city_id_val')
            ->andWhere('w.date = :datetime_val')
            ->setParameter('city_id_val', $city_id)
            ->setParameter('datetime_val', $datetime)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
