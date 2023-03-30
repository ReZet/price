<?php
namespace App\Service;

use App\Entity\Price;
use App\Repository\CategoryRepository;
use App\Repository\PriceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PriceCreationService
{
    private EntityManagerInterface $em;
    private CategoryRepository $categoryRepository;
    private PriceRepository $priceRepository;

    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository, PriceRepository  $priceRepository)
    {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
        $this->priceRepository = $priceRepository;
    }

    public function createPrice(string $amount, string $currency, string $name, string $variant, int $categoryId): Price
    {
        if ($this->priceRepository->findOneBy([
            'name' => $name,
            'variant' => $variant,
            'currency' => $currency,
        ])) {
            throw new \RuntimeException('The product already has a price');
        }

        if (!$category = $this->categoryRepository->find($categoryId)) {
            throw new \RuntimeException('The category is not found');
        }

        $productPrice = new Price();
        $productPrice->setPrice($amount)
            ->setCategory($category)
            ->setName($name)
            ->setCurrency($currency)
            ->setVariant($variant);

        $this->em->persist($productPrice);

        if (!$category->isDifferentSizePrices()) {
            $variantsPrices = $this->priceRepository->findBy([
                'name' => $name,
                'currency' => $currency
            ]);

            foreach ($variantsPrices as $variantsPrice) {
                $variantsPrice->setPrice($amount);
            }
        }

        $this->em->flush();

        return $productPrice;
    }
}