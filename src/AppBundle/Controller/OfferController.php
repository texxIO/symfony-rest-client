<?php

namespace AppBundle\Controller;

use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;
class OfferController extends Controller
{

    /**
     * @Route("dashboard/offers")
     *
     */
    public function indexAction()
    {

        $apiRequestClient = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->getParameter('offers_api_url'),
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);

        $viewData = ['offers' => [],
            'messages' => []];

        try {
            $offers = $apiRequestClient->get('api/offers');
            if ($offers->getStatusCode() == 200) {
                $viewData['offers'] = $offers->getBody();
            }
            else
            {
                $viewData['messages'][] = 'Failed API response code';
                $viewData['error'] = true;
            }

        } catch (RequestException $e) {
            //This is just for demo only! In production a more general message will be displayed.
            $viewData['messages'][] = $e->getMessage();
            $viewData['error'] = true;

        }
        return $this->render('default/offers/index.html.twig', $viewData);
    }

    /**
     * @param int $id
     * @Route("dashboard/offers/{offerId}", requirements={"offerId"="\d+"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction( int $offerId )
    {
        $viewData = ['offer' => [],
            'errors' => []];

        try {
            $offers = $this->apiRequestClient->get('api/offers/'.$offerId);
            if ($offers->getStatusCode() == 200) {
                $viewData['offer'] = $offers->getBody();
            }
            else
            {
                $viewData['messages'][] = 'Failed API response code';
                $viewData['error'] = true;
            }

        } catch (RequestException $e) {
            //This is just for demo only! In production a more general message will be displayed.
            $viewData['messages'][] = $e->getMessage();
            $viewData['error'] = true;

        }


        return $this->render('default/offers/show.html.twig', $viewData);
    }


    /**
     * @param Request $request
     * @Route("dashboard/offers/add")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction( Request $request )
    {
        if ( $request->getMethod() == 'POST' )
        {
            echo '<pre>' . print_r($request->request->all(),true) . '</pre>';
            exit($request->request->all());
        }
        return $this->render('default/offers/add.html.twig');
    }

}
