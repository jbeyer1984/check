<?php


namespace Check\App\Product;


use Check\Persistence\MapperInterface;

class ProductMapper implements MapperInterface
{
    /**
     * @var Product
     */
    private $product;

    /**
     * ProductMapper constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        $array = [
            'id' => 0
        ];
        if (!empty($this->product->getId())) {
            $array = [
                'id' => $this->product->getId(),
            ];
        }
        return array_merge(
            $array,
            [
                'name' => $this->product->getName(),
                'description' => $this->product->getDescription(),
                'created' => $this->product->getCreated()
            ]
        );
    }


}