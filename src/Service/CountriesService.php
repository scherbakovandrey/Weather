<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Country;

class CountriesService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAll($page = 1)
    {
        if ( !empty($page) && ( !is_numeric($page) || $page < 1)) {
            return [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'error' => 'Page parameter MUST be equal or more than 1.',
            ];
        }

        $repository = $this->entityManager->getRepository(Country::class);

        $countries = $repository->getAll($page);

        if (empty($countries))
        {
            return [
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'error' => 'Page parameter is too high.',
            ];
        }

        return [
            'status' => Response::HTTP_OK,
            'error' => '',
            'countries' => $countries
        ];
    }
}