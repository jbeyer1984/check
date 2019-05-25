<?php


namespace Check\App\Product\Factory;


use Check\App\Product\Product;
use Check\App\Product\ProductMapper;
use DI\Container;

class ProductFactory
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
     * @param array $productRecordSet
     * @return Product
     */
    public function createProductByRecordSet(array $productRecordSet): Product
    {
        $id = $productRecordSet['id'];
        $name = $productRecordSet['name'];
        $description = $productRecordSet['description'];
        $created = $productRecordSet['created'];
        
        return $this->createProduct($id, $name, $description, $created);
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string $created
     * @return Product
     */
    public function createProduct(int $id, string $name, string $description, string $created)
    {
        return new Product($id, $name, $description, $created);
    }

    /**
     * @param string $name
     * @param string $description
     * @param string $created
     * @return Product
     */
    public function createProductPrototype(string $name, string $description, string $created): Product
    {
        return $this->createProduct(0, $name, $description, $created);
    }

    /**
     * @return Product
     */
    public function createProductDummy(): Product
    {
        return $this->createProduct(0, '', '', '');
    }

    /**
     * @param Product $product
     * @return ProductMapper
     */
    public function createProductMapper(Product $product): ProductMapper
    {
        return new ProductMapper($product);
    }
}