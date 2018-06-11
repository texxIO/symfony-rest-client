<?php

namespace AppBundle\Controller;

use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GuzzleHttp\Client;

class OfferController extends Controller
{
    private $apiRequestClient;

    public function __construct()
    {
        $this->apiRequestClient = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://tradus.local',
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);
    }

    /**
     * @Route("dashboard/offers")
     *  @ Route("/api/offers", methods={"GET"})
     */
    public function indexAction()
    {
        $viewData = ['offers' => [],
            'errors' => []];

        try {
            $offers = $this->apiRequestClient->get('api/offers');
            if ($offers->getStatusCode() == 201) {
                $viewData['offers'] = $offers->getBody();
            }
            else
            {
                $viewData['errors'][] = 'Failed API response code';
            }

        } catch (RequestException $e) {
            $viewData['errors'] = $e->getMessage();

        }

        return $this->render('default/offers/index.html.twig', $viewData);
    }

}
