<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\CountriesService;

class CountriesController extends AbstractController
{
    public function index(Request $request, CountriesService $countriesService)
    {
        $page = $request->get('page') ?? 1;

        //we make this as a service so we are free to use it from the command line or from the web interface
        $apiResponse = $countriesService->getAll($page);

        if ($apiResponse['status'] != Response::HTTP_OK) {
            return new JsonResponse($apiResponse, $apiResponse['status']);
        }

        return JsonResponse::create($apiResponse['countries']);
    }
}