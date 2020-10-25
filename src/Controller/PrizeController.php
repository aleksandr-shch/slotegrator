<?php


namespace App\Controller;

use App\Service\PrizeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PrizeController extends AbstractController
{
    /**
     * @var Security
     */
    private Security $security;

    private PrizeService $prizeService;

    /**
     * PrizeController constructor.
     * @param Security $security
     * @param PrizeService $prizeService
     */
    public function __construct( Security $security, PrizeService $prizeService)
    {
        $this->security = $security;
        $this->prizeService = $prizeService;
    }

    /**
     * @Route("/prize", name="app_homepage")
     */
    public function index(): Response
    {
        return $this->render('prize/index.html.twig', ['controller_name' => 'PrizeController', 'user' => $this->security->getUser()]);
    }

    /**
     * @Route("/ajax_prize", name="app_ajax_prize", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxPrize(Request $request): JsonResponse
    {
        if($request->request->has('user'))
        {
            $message = $this->prizeService->getPrize($request->request->get('user'));

            return $this->json(['success' => true, 'message'=> $message]);
        }
        else
        {
            return $this->json(['success' => false, 'message'=> 'Error']);
        }
    }
}