<?php
/**
 * Created by PhpStorm.
 * User: Jens
 * Date: 11.05.2019
 * Time: 19:55
 */

namespace Check\Persistence\Repository\Decorator;

use Check\Persistence\Condition\ConditionContainer\ConditionContainer;
use Check\Persistence\Repository\BaseRepositoryInterface;
use Check\Persistence\Repository\Table\Table;
use Check\Persistence\Temporary\TemporaryPersistenceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RepositoryTemporaryPersistenceDecoratorTest extends TestCase
{
    /**
     * @var RepositoryTemporaryPersistenceDecorator
     */
    private $repositoryTemporaryPersistenceDecorator;

    /**
     * @var BaseRepositoryInterface|MockObject
     */
    private $repositoryMock;

    /**
     * @var TemporaryPersistenceInterface|MockObject
     */
    private $temporaryPersistenceMock;

    public function setUp()
    {
        $this->repositoryMock                          = $this->createMock(BaseRepositoryInterface::class);
        $this->temporaryPersistenceMock                = $this->createMock(TemporaryPersistenceInterface::class);
        $this->repositoryTemporaryPersistenceDecorator = new RepositoryTemporaryPersistenceDecorator(
            $this->repositoryMock, $this->temporaryPersistenceMock
        );
    }
    
    public function canBeCreatedTest()
    {
        $repositoryTemporaryPersistenceDecorator = new RepositoryTemporaryPersistenceDecorator(
            $this->repositoryMock, $this->temporaryPersistenceMock
        );

        $this->assertInstanceOf(
            RepositoryTemporaryPersistenceDecorator::class, $repositoryTemporaryPersistenceDecorator
        );
    }
    
    public function testSelect()
    {
        /** @var Table|MockObject $table */
        $table = $this->createMock(Table::class);
        /** @var ConditionContainer|MockObject $conditionContainer */
        $conditionContainer = $this->createMock(ConditionContainer::class);
        
        $this->repositoryMock->expects($this->at(0))->method('select')->willReturn([]);
        $result = $this->repositoryTemporaryPersistenceDecorator->select($table, $conditionContainer);
        $this->assertEquals([], $result);
        
        $this->repositoryMock->expects($this->at(0))->method('select')->willReturn(['id' => 0]);
        $result = $this->repositoryTemporaryPersistenceDecorator->select($table, $conditionContainer);
        $this->assertEquals(['id' => 0], $result);
    }

    public function testSave()
    {
        /** @var Table|MockObject $table */
        $table = $this->createMock(Table::class);
        $table->expects($this->any())->method('getPrimaryIdentifier')->willReturn('id');
        $parameterInput = ['id' => 0, 'field_1' => 'field_1'];
        $parameterOutput = $this->repositoryTemporaryPersistenceDecorator->save($table, $parameterInput);
        $this->assertEquals($parameterInput, $parameterOutput);
    }

    public function testDelete()
    {
        /** @var Table|MockObject $table */
        $table = $this->createMock(Table::class);
        $table->expects($this->any())->method('getPrimaryIdentifier')->willReturn('id');
        $parameterInput = ['id' => 0, 'field_1' => 'field_1'];
        $parameterOutput = $this->repositoryTemporaryPersistenceDecorator->save($table, $parameterInput);
        $this->assertEquals($parameterInput, $parameterOutput);

        $this->repositoryTemporaryPersistenceDecorator->delete($table, $parameterOutput);
    }
}
