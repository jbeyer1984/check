<?php


namespace Check\App\Product\Service;


use Check\App\Product\Product;
use Check\App\Product\Repository\ProductRepository;
use DI\Container;

class ProductService
{
    /**
     * @var Container
     */
    private $container;

    /**
     * UserFactory constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @return Product
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \Exception
     */
    public function findProductById(int $id): Product
    {
        $productRepository = $this->container->get(ProductRepository::class);
        $product = $productRepository->findById($id);

        return $product;
    }

    /**
     * @return array
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function findAllProducts(): array
    {
        $productRepository = $this->container->get(ProductRepository::class);
        $products = $productRepository->findAll();
        
        return $products;
    }

    /**
     * @param Product $product
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function changeProduct(Product $product)
    {
        $productRepository = $this->container->get(ProductRepository::class);
        $productRepository->save($product);
    }
}