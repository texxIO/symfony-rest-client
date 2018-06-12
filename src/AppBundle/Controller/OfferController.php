<?php

namespace AppBundle\Controller;

use AppBundle\Services\ApiRequestService;
use GuzzleHttp\Exception\RequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OfferController extends Controller
{
    private $apiRequestClient;

    public function __construct(ApiRequestService $apiRequestService)
    {
        $this->apiRequestClient = $apiRequestService->getApiRequestClient();
    }

    /**
     * @Route("dashboard", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
    /**
     * @Route("dashboard/offers", name="offers_list")
     *
     */
    public function listAction()
    {
        $viewData = ['offers' => [],
            'messages' => [],
            'errors' => false];


        try {
            $offers = $this->apiRequestClient->get('api/offers');
            if ($offers->getStatusCode() == 200) {
                $viewData['offers'] = json_decode($offers->getBody());
            } else {
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
     * @param int $offerId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("dashboard/offers/{offerId}", requirements={"offerId"="\d+"}, name="offers_show")
     */
    public function showAction(int $offerId)
    {
        $viewData = ['offer' => [],
            'messages' => [],
            'errors' => false];

        try {
            $offers = $this->apiRequestClient->get('api/offers/' . $offerId);
            if ($offers->getStatusCode() == 200) {
                $viewData['offer'] = json_decode($offers->getBody());
            } else {
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @Route("dashboard/offers/add",  name="offers_add")
     */
    public function addAction(Request $request)
    {
        $viewData = [ 'messages' => [],
            'errors' => false];

        if ($request->getMethod() == 'POST') {

            try
            {
                $offerData = $request->request->all();
                $postRequest = $this->apiRequestClient->request('POST', 'api/offer', ['form_params' => $offerData]);

                $postResponse = json_decode($postRequest->getBody(), true);
                $viewData['messages'][] = 'Offer added with ID:' . $postResponse['offerId'];

            }
            catch( RequestException $e )
            {
                $viewData['messages'][] = $e->getMessage();
                $viewData['errors'] = true;
            }

        }
        return $this->render('default/offers/add.html.twig', $viewData);
    }

    /**
     * TODO
     * @param Request $request
     * @Route("dashboard/offers/edit/{offerId}", requirements={"offerId"="\d+"}, name="offers_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $viewData = [];
        return $this->render('default/offers/add.html.twig', $viewData);
    }

     /**
     * @param Request $request
     * @Route("dashboard/offers/delete/{offerId}",requirements={"offerId"="\d+"}, name="offers_delete")
     */
    public function deleteAction(Request $request)
    {

    }

}
