<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiPriceController extends AbstractController
{
    /**
     * @Route("/api/price", name="app_api_price")
     */
    public function index(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();
        return $this->json(array_map(static function ($cat) {
            return [
                'id' => $cat->getId(),
                'name' => $cat->getName(),
                'priceType' => $cat->isDifferentSizePrices(),
            ];
        }, $categories));
    }
}
