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
            $viewData = ['offers' => []];


            try {
                $offers = $this->apiRequestClient->get('api/offers');
                $viewData['offers'] = json_decode($offers->getBody());

            } catch (RequestException $e) {

                $this->addFlash(
                    'danger',
                    'Failed to fetch offers'
                );

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

            try {
                $offers = $this->apiRequestClient->get('api/offers/' . $offerId);
                $viewData['offer'] = json_decode($offers->getBody());
                return $this->render('default/offers/show.html.twig', $viewData);
            } catch (RequestException $e) {

                $this->addFlash(
                    'danger',
                    'Failed to fetch offer data'
                );
                return $this->redirectToRoute("offers_list");

            }

        }

        /**
         * @param Request $request
         * @return \Symfony\Component\HttpFoundation\Response
         * @throws \GuzzleHttp\Exception\GuzzleException
         * @Route("dashboard/offers/add",  name="offers_add")
         */
        public function addAction(Request $request)
        {
            if ($request->getMethod() == 'POST') {

                try {
                    $offerData = $request->request->all();
                    $postRequest = $this->apiRequestClient->request('POST', 'api/offer', ['form_params' => $offerData]);

                    $postResponse = json_decode($postRequest->getBody(), true);

                    $this->addFlash(
                        'success',
                        'Offer added with ID:' . $postResponse['offerId']
                    );

                } catch (RequestException $e) {
                    $this->addFlash(
                        'danger',
                        $e->getMessage()
                    );
                }
                return $this->redirectToRoute("offers_list");
            }

            return $this->render('default/offers/add.html.twig');
        }

        /**
         * @param Request $request
         * @Route("dashboard/offers/edit/{offerId}", name="offers_edit", methods={"GET", "POST"})
         * @return \Symfony\Component\HttpFoundation\Response
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function editAction(int $offerId, Request $request)
        {
            $viewData = [];
            if ($request->getMethod() == 'POST') {
                $updateData['title'] = $request->request->get('title');
                $updateData['description'] = $request->request->get('description');
                $updateData['email'] = $request->request->get('email');
                $updateData['image_url'] = $request->request->get('image_url');

                try {
                    $apiResponse = $this->apiRequestClient->request("PUT", "api/update/" . $offerId,
                        ['form_params' => $updateData]);

                    $this->addFlash(
                        'success',
                        'Offer updated'
                    );

                } catch (RequestException $e) {
                    $this->addFlash(
                        'danger',
                        'Offer update failed'
                    );
                }

                return $this->redirectToRoute('offers_list');
            } else {

                try {
                    $offers = $this->apiRequestClient->get('api/offers/' . $offerId);
                    $viewData['offer'] = json_decode($offers->getBody());
                    return $this->render('default/offers/edit.html.twig', $viewData);

                } catch (RequestException $e) {

                    $this->addFlash(
                        'danger',
                        'Failed to fetch offer data'
                    );
                    return $this->redirectToRoute("offers_list");
                }


            }
        }

        /**
         * @param Request $request
         * @Route("dashboard/offers/delete/{offerId}",requirements={"offerId"="\d+"}, name="offers_delete")
         * @return \Symfony\Component\HttpFoundation\Response
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function deleteAction(int $offerId)
        {

            try {
                $postRequest = $this->apiRequestClient->request('GET', '/api/offers/delete/' . $offerId);

                $this->addFlash(
                    'success',
                    'Delete was successfully. ID: ' . $offerId
                );

            } catch (RequestException $e) {

                $this->addFlash(
                    'danger',
                    'Delete failed. ID:' . $offerId
                );

            }
            return $this->redirectToRoute("offers_list");

        }
    }
