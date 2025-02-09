<?php

namespace Ifedko\DoctrineDbalPagination\Test;

use Mockery;
use PHPUnit\Framework\TestCase;

class ListBuilderTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testConfigureSuccess()
    {
        $dbConnection = self::createDbConnectionMock();
        $parameters = [
            'param1' => 'value1',
            'sortBy' => 'field1',
        ];

        $listBuilderMock = Mockery::mock('Ifedko\DoctrineDbalPagination\ListBuilder[configure]', [$dbConnection])
            ->makePartial();
        $listBuilderMock->configure($parameters);
    }

    public function testQueryReturnQueryBuilderSuccess()
    {
        $dbConnection = self::createDbConnectionMock();
        $parameters = [
            'param1' => 'value1',
            'sortBy' => 'field1',
        ];
        $queryBuilderMock = Mockery::mock('Doctrine\DBAL\Query\QueryBuilder');

        $listBuilderMock = Mockery::mock('Ifedko\DoctrineDbalPagination\ListBuilder[baseQuery]', [$dbConnection])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $listBuilderMock->shouldReceive('baseQuery')->andReturn($queryBuilderMock);

        $listBuilderMock->configure($parameters);
        $queryBuilder = $listBuilderMock->query();

        $this->assertInstanceOf('Doctrine\DBAL\Query\QueryBuilder', $queryBuilder);
    }

    public function testTotalQueryReturnQueryBuilderSuccess()
    {
        $dbConnection = self::createDbConnectionMock();
        $parameters = [
            'param1' => 'value1',
            'sortBy' => 'field1',
        ];
        $queryBuilderMock = Mockery::mock('Doctrine\DBAL\Query\QueryBuilder');
        $queryBuilderMock
            ->shouldReceive('resetQueryPart')
            ->andReturn($queryBuilderMock);

        $queryBuilderMock
            ->shouldReceive('select')
            ->andReturn($queryBuilderMock);

        $listBuilderMock = Mockery::mock('Ifedko\DoctrineDbalPagination\ListBuilder[baseQuery]', [$dbConnection])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $listBuilderMock->shouldReceive('baseQuery')->andReturn($queryBuilderMock);

        $listBuilderMock->configure($parameters);
        $queryBuilder = $listBuilderMock->totalQuery();

        $this->assertInstanceOf('Doctrine\DBAL\Query\QueryBuilder', $queryBuilder);
    }

    public function testTotalQueryResetSelectPart()
    {
        $dbConnection = self::createDbConnectionMock();
        $queryBuilderMock = Mockery::mock('Doctrine\DBAL\Query\QueryBuilder');
        $queryBuilderMock
            ->shouldReceive('resetQueryPart')
            ->with('select')
            ->andReturn($queryBuilderMock)
            ->once();

        $queryBuilderMock
            ->shouldReceive('select')
            ->with('1')
            ->andReturn($queryBuilderMock)
            ->once();

        $listBuilderMock = Mockery::mock('Ifedko\DoctrineDbalPagination\ListBuilder', [$dbConnection])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $listBuilderMock->shouldReceive('baseQuery')->andReturn($queryBuilderMock);

        $listBuilderMock->totalQuery();
    }

    private static function createDbConnectionMock()
    {
        return Mockery::mock('\Doctrine\DBAL\Connection');
    }
}
