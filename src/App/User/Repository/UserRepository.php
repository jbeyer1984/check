<?php


namespace Check\App\User\Repository;


use Check\Persistence\Condition\Condition;
use Check\Persistence\Condition\ConditionContainer\ConditionContainer;
use Check\App\User\Factory\UserFactory;
use Check\App\User\LoggedInUser;
use Check\Persistence\ConditionQueryInterface;
use Check\Persistence\Repository\BaseRepositoryInterface;
use Check\Persistence\Repository\EntityRepositoryInterface;
use Check\Persistence\Repository\Table\Table;
use Exception;

class UserRepository implements EntityRepositoryInterface
{
    /**
     * @var BaseRepositoryInterface
     */
    private $persistence;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var Table
     */
    private $table = null;

    /**
     * UserRepository constructor.
     * @param BaseRepositoryInterface $persistence
     * @param UserFactory $userFactory
     */
    public function __construct(BaseRepositoryInterface $persistence, UserFactory $userFactory)
    {
        $this->persistence = $persistence;
        $this->userFactory = $userFactory;
        $this->table = Table::withPrimaryKey(
            'user',
            [
                'id'
            ],
            [
                'name',
                'email',
                'password'
            ]
        );
    }
    
    

    /**
     * @param int $id
     * @return LoggedInUser
     * @throws Exception
     */
    public function findById(int $id): LoggedInUser
    {
        $conditionContainer = ConditionContainer::And();
        $conditionContainer->add(Condition::operator($this->table->getPrimaryIdentifier(), '=', $id));
        $result = $this->persistence->select($this->table, $conditionContainer);
        
        if (0 === count($result)) {
            return $this->userFactory->createLoggedInUserDummy();
//            throw new \Exception(sprintf('User not found in table=%s with id=%s', $this->table->getName(), $id));
        }
        
        if (1 < count($result)) {
            throw new Exception(sprintf('User double existing in table=%s with id=%s, THAT SHOULD NOT HAPPEN', $this->table->getName(), $id));
        }
        
        return $this->userFactory->createLoggedInUserByRecordSet($result[0]);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $result = $this->persistence->select($this->table);

        $loggedInUsers = array_map(function($row) {
            return $this->userFactory->createLoggedInUserByRecordSet($row);
        }, $result);

        if (empty($loggedInUsers)) {
            return [
                $this->userFactory->createLoggedInUserDummy()
            ];
        }

        return $loggedInUsers;
    }

    /**
     * @param ConditionQueryInterface $conditionContainer
     * @return LoggedInUser[]
     * @throws Exception
     */
    public function findBy(ConditionQueryInterface $conditionContainer): array
    {
        $result = $this->persistence->select($this->table, $conditionContainer);

        if (empty($result)) {
            return [];
        }

        $loggedInUsers = array_map(function($row) {
            return $this->userFactory->createLoggedInUserByRecordSet($row);
        }, $result);
        
        return $loggedInUsers;
    }

    /**
     * @param LoggedInUser $loggedInUser
     */
    public function save(LoggedInUser $loggedInUser)
    {
        $loggedInUserMapper = $this->userFactory->createLoggedInUserMapper($loggedInUser);
        $this->persistence->save($this->table, $loggedInUserMapper->getMap());
    }

    public function delete(LoggedInUser $loggedInUser)
    {
        $loggedInUserMapper = $this->userFactory->createLoggedInUserMapper($loggedInUser);
        $this->persistence->delete($this->table, $loggedInUserMapper->getMap());
    }
}