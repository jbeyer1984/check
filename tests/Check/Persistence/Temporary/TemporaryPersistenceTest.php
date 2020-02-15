<?php
/**
 * Created by PhpStorm.
 * User: Jens
 * Date: 28.04.2019
 * Time: 11:23
 */

namespace Check\Persistence\Temporary;


use Check\Persistence\Repository\Table\Table;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StdClass;

class TemporaryPersistenceTest extends TestCase
{
    /**
     * @var TemporaryPersistence
     */
    private $temporaryPersistence;

    /**
     * @var Table|MockObject
     */
    private $tableMock;

    public function setUp()
    {
        $this->temporaryPersistence = new TemporaryPersistence();
        
        $this->tableMock = $this->createMock(Table::class);
        $this->tableMock->expects($this->any())->method('hasPrimaryKey')->willReturn(true);
        $this->tableMock->expects($this->any())->method('getName')->willReturn('table_1');
        $this->tableMock->expects($this->any())->method('getPrimaryIdentifier')->willReturn('id_1');
        $this->tableMock->expects($this->any())->method('getMap')->willReturn(
            [
                'id_1',
                'field_1'
            ]
        );
    }
    
    public function testCanBeCreated()
    {
        $this->assertInstanceOf(TemporaryPersistence::class, $this->temporaryPersistence);
    }

    /**
     * @dataProvider hasUpdateProvider
     * @param array $set
     * @param array $changedSet
     * @throws Exception
     */
    public function testHasUpdate($set, $changedSet)
    {
        $this->temporaryPersistence->update($this->tableMock, $set);
        $this->assertTrue($this->temporaryPersistence->hasUpdate($this->tableMock, $changedSet));
    }

    /**
     * @return array
     */
    public function hasUpdateProvider()
    {
        return [
            [
                [ // set
                    'id_1' => 1,
                    'field_1' => 'field_1',
                ],
                [ // changed set
                    'id_1' => 1,
                    'field_1' => 'field_1_changed',
                ],    
            ],
            [
                [
                    'id_1' => 1,
                    'field_1' => 'field_1',
                ],
                [
                    'id_1' => 1,
                    'field_1' => 2,
                ],

            ],
            [
                [
                    'id_1' => 1,
                    'field_1' => 0,
                ],
                [
                    'id_1' => 1,
                    'field_1' => false,
                ],

            ],
            [
                [
                    'id_1' => 1,
                    'field_1' => 1,
                ],
                [
                    'id_1' => 1,
                    'field_1' => true,
                ],

            ],
            [
                [
                    'id_1' => 1,
                    'field_1' => false,
                ],
                [
                    'id_1' => 1,
                    'field_1' => true,
                ],

            ],
            [ // this case should not happen in db there should only be easy types
                [
                    'id_1' => 1,
                    'field_1' => new StdClass(),
                ],
                [
                    'id_1' => 1,
                    'field_1' => new StdClass(),
                ],

            ],
        ];
    }

    /**
     * @dataProvider hasNoUpdateProvider
     * @param array $set
     * @param array $changedSet
     * @throws Exception
     */
    public function testHasNoUpdate($set, $changedSet)
    {
        $this->temporaryPersistence->update($this->tableMock, $set);
        $this->temporaryPersistence->update($this->tableMock, $changedSet);
        $this->assertFalse($this->temporaryPersistence->hasUpdate($this->tableMock, $changedSet));
    }

    /**
     * @return array
     */
    public function hasNoUpdateProvider()
    {
        return [
            [
                [ // set
                    'id_1' => 1,
                    'field_1' => 0,
                ],
                [ // set to check for change
                    'id_1' => 1,
                    'field_1' => 0,
                ],
            ],
            [
                [
                    'id_1' => 1,
                    'field_1' => 0,
                ],
                [
                    'id_1' => 1,
                    'field_1' => 0,
                    'field_2' => 0,
                ],
            ],
        ];
    }

    /**
     * @throws Exception
     */
    public function testHasUpdateException()
    {
        $set = [[],[]];
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('update is only allowed for one entry');
        $this->temporaryPersistence->hasUpdate($this->tableMock, $set);
    }

    /**
     * @throws Exception
     */
    public function testUpdate()
    {
        $set = [
            'id_1' => 0,
            'field_1' => 'field_1'
        ];
        $this->temporaryPersistence->update($this->tableMock, $set);
        $set['field_1'] = 'field_1_changed';
        $this->assertTrue($this->temporaryPersistence->hasUpdate($this->tableMock, $set));
        $this->temporaryPersistence->update($this->tableMock, $set);
        $this->assertTrue($this->temporaryPersistence->hasUpdate($this->tableMock, $set));
        $this->temporaryPersistence->update($this->tableMock, $set);
        $this->assertFalse($this->temporaryPersistence->hasUpdate($this->tableMock, $set));
    }

    /**
     * @throws Exception
     */
    public function testUpdateException()
    {
        $set = [
//            'id_not_set' => 0,
            'field_1' => 'field_1_changed'
        ];
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('not all fields found in table=table_1 with keys=id_1');
        $this->temporaryPersistence->update($this->tableMock, $set);
        
        
        $set             = [
            'id_1' => 0,
            'field_1' => 'field_1'
        ];
        $this->tableMock = $this->createMock(Table::class);
        $this->tableMock->expects($this->any())->method('hasPrimaryKey')->willReturn(false);
        $this->tableMock->expects($this->any())->method('getName')->willReturn('table_1');
        $this->tableMock->expects($this->any())->method('getPrimaryIdentifier')->willReturn('id_1');

        $this->expectExceptionMessage('table table_1 has no primary key');
        $this->temporaryPersistence->update($this->tableMock, $set);
    }

    /**
     * @dataProvider getMapToUpdateProvider
     * @param array $set
     * @param array $setChanged
     * @param array $expectedMapToUpdate
     * @throws Exception
     */
    public function testGetMapToUpdate($set, $setChanged, $expectedMapToUpdate)
    {
        $this->temporaryPersistence->update($this->tableMock, $set);
        $mapToUpdate = $this->temporaryPersistence->getMapToUpdate($this->tableMock, $setChanged);
        $this->assertEquals(
            $expectedMapToUpdate,
            $mapToUpdate
        );
    }

    public function getMapToUpdateProvider()
    {
        return [
            [
                [ // set
                    'id_1' => 1,
                    'field_1' => 'field_1',
                ],
                [ // changedSet
                    'id_1' => 1,
                    'field_1' => 'field_1_changed',
                ],
                [ // expected map to update
                    'field_1' => 'field_1_changed'
                ]
            ],
            [
                [
                  'id_1' => 1,
                  'field_1' => 'field_1',
                ],
                [
                  'id_1' => 1,
                  'field_2' => 'field_1',
                ],
                [
                    
                ]
            ],
        ];
    }

    public function testExists()
    {
        $result = [
            'id_1' => 1,
            'field_1' => 'field_1',
        ];

        $this->assertFalse($this->temporaryPersistence->exists($this->tableMock, $result));
        $this->temporaryPersistence->update($this->tableMock, $result);
        $this->assertTrue($this->temporaryPersistence->exists($this->tableMock, $result));
    }

    public function testDelete()
    {
        $result = [
            'id_1' => 1,
            'field_1' => 'field_1',
        ];

        $this->temporaryPersistence->update($this->tableMock, $result);
        $this->assertTrue($this->temporaryPersistence->exists($this->tableMock, $result));
        $this->temporaryPersistence->delete($this->tableMock, $result);
        $this->assertFalse($this->temporaryPersistence->exists($this->tableMock, $result));
    }
}
