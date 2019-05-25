<?php


namespace Check\App\User\Repository;


use Check\Persistence\Condition\Condition;
use Check\Persistence\Condition\ConditionContainer\ConditionContainer;
use Check\App\User\Factory\UserSessionFactory;
use Check\App\User\UserSession;
use Check\Persistence\ConditionQueryInterface;
use Check\Persistence\Repository\BaseRepositoryInterface;
use Check\Persistence\Repository\EntityRepositoryInterface;
use Check\Persistence\Repository\Table\Table;

class UserSessionRepository implements EntityRepositoryInterface
{
    /**
     * @var BaseRepositoryInterface
     */
    private $persistence;

    /**
     * @var UserSessionFactory
     */
    private $userSessionFactory;

    /**
     * @var string
     */
    private $table = null;

    /**
     * UserSessionRepository constructor.
     * @param BaseRepositoryInterface $persistence
     * @param UserSessionFactory $userSessionFactory
     */
    public function __construct(BaseRepositoryInterface $persistence, UserSessionFactory $userSessionFactory)
    {
        $this->persistence        = $persistence;
        $this->userSessionFactory = $userSessionFactory;
        $this->table = Table::withPrimaryKey(
            'user_session',
            [
                'id'
            ],
            [
                'user_id',
                'session_id',
            ]
        );
    }

    /**
     * @param int $id
     * @return UserSession
     * @throws \Exception
     */
    public function findById(int $id): UserSession
    {
        $conditionContainer = ConditionContainer::And();
        $conditionContainer->add(Condition::operator('id', '=', $id));
        $result = $this->persistence->select($this->table, $conditionContainer);

        if (0 === count($result)) {
            throw new \Exception(sprintf('UserSession not found in table=%s with id=%s', $this->table->getName(), $id));
        }

        if (1 < count($result)) {
            throw new \Exception(sprintf('UserSession double existing in table=%s with id=%s, THAT SHOULD NOT HAPPEN', $this->table->getName(), $id));
        }

        return $this->userSessionFactory->createUserSessionByRecordSet($result[0]);
    }

    /**
     * @param ConditionQueryInterface $conditionContainer
     * @return UserSession[]
     * @throws \Exception
     */
    public function findBy(ConditionQueryInterface $conditionContainer): array
    {
        $result = $this->persistence->select($this->table, $conditionContainer);

        if (empty($result)) {
            return [
                $this->userSessionFactory->createUserSessionDummy()
            ];
        }

        $userSessions = array_map(function($row) {
            return $this->userSessionFactory->createUserSessionByRecordSet($row);
        }, $result);

        return $userSessions;
    }

    /**
     * @param UserSession $userSession
     */
    public function save(UserSession $userSession)
    {
        $userSessionMapper = $this->userSessionFactory->createUserSessionMapper($userSession);
        $this->persistence->save($this->table, $userSessionMapper->getMap());
    }
}