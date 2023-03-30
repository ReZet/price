<?php

namespace App\Controller;

use App\Repository\PriceRepository;
use App\Service\PriceCreationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiPriceController extends AbstractController
{
    /**
     * @Route("/api/price", name="api_price_list", methods={"GET"})
     */
    public function index(PriceRepository $priceRepository): JsonResponse
    {
        $prices = $priceRepository->findAll();
        return $this->json(array_map(static function ($price) {
            return [
                'id' => $price->getId(),
                'name' => $price->getName(),
                'variant' => $price->getVariant(),
                'category' => $price->getCategory() ? $price->getCategory()->getName() : null,
                'price' => $price->getPrice()
            ];
        }, $prices));
    }

    /**
     * @Route("/api/price", name="api_price_create", methods={"POST"})
     */
    public function createPrice(
        Request            $request,
        PriceCreationService $priceCreationService
    ): JsonResponse {
        try {
            //replace to json and serialize it to dto
            $name = $request->get('name');
            $variant = $request->get('variant');
            $price = $request->get('price');
            $currency = $request->get('currency');
            $categoryId = $request->get('category');

            //use validator
            if (empty($name) || empty($variant) || empty($price) || empty($currency) || empty($categoryId)) {
                throw new \RuntimeException('Provided not all data: '  . json_encode($request->toArray()));
            }

            $productPrice = $priceCreationService->createPrice($price, $currency, $name, $variant, $categoryId);

            //transform entity to dto
            return $this->json([
                    'status' => 'success',
                    'context' => $productPrice,
                    'message' => null
                ]
            );
        //create Exception Handler and different Exceptions
        } catch (\Throwable $e) {
            return $this->json([
                'status' => 'error',
                'context' => null,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
