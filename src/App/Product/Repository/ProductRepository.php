<?php


namespace Check\App\Product\Repository;

use Check\App\Product\Factory\ProductFactory;
use Check\App\Product\Product;
use Check\Persistence\Condition\Condition;
use Check\Persistence\Condition\ConditionContainer\ConditionContainer;
use Check\Persistence\ConditionQueryInterface;
use Check\Persistence\Repository\BaseRepositoryInterface;
use Check\Persistence\Repository\EntityRepositoryInterface;
use Check\Persistence\Repository\Table\Table;

class ProductRepository implements EntityRepositoryInterface 
{
    /**
     * @var BaseRepositoryInterface
     */
    private $persistence;

    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var Table
     */
    private $table = null;

    /**
     * ProductRepository constructor.
     * @param BaseRepositoryInterface $persistence
     * @param ProductFactory $productFactory
     */
    public function __construct(BaseRepositoryInterface $persistence, ProductFactory $productFactory)
    {
        $this->persistence    = $persistence;
        $this->productFactory = $productFactory;
        $this->table          = Table::withPrimaryKey(
            'product',
            [
                'id'
            ],
            [
                'name',
                'description',
                'created'
            ]
        );
    }


    /**
     * @param int $id
     * @return Product
     * @throws \Exception
     */
    public function findById(int $id): Product
    {
        $conditionContainer = ConditionContainer::And();
        $conditionContainer->add(Condition::operator('id', '=', $id));
        $result = $this->persistence->select($this->table, $conditionContainer);

        if (0 === count($result)) {
            throw new \Exception(sprintf('Product not found in table=%s with id=%s', $this->table->getName(), $id));
        }

        if (1 < count($result)) {
            throw new \Exception(sprintf('Product double existing in table=%s with id=%s, THAT SHOULD NOT HAPPEN', $this->table->getName(), $id));
        }

        return $this->productFactory->createProductByRecordSet($result[0]);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $result = $this->persistence->select($this->table);

        $products = array_map(function($row) {
            return $this->productFactory->createProductByRecordSet($row);
        }, $result);

        return $products;
    }

    /**
     * @param ConditionQueryInterface $conditionContainer
     * @return Product[]
     * @throws \Exception
     */
    public function findBy(ConditionQueryInterface $conditionContainer): array
    {
        $result = $this->persistence->select($this->table, $conditionContainer);

        $loggedInUsers = array_map(function($row) {
            return $this->productFactory->createProductByRecordSet($row);
        }, $result);

        if (empty($loggedInUsers)) {
            return [
                $this->productFactory->createProductDummy()
            ];
        }

        return $loggedInUsers;
    }

    /**
     * @param Product $product
     */
    public function save(Product $product)
    {
        $productMapper = $this->productFactory->createProductMapper($product);
        $this->persistence->save($this->table, $productMapper->getMap());
    }
}