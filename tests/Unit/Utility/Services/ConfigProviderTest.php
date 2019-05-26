<?php

/** @noinspection PhpParamsInspection */

declare(strict_types=1);

namespace ReliqArts\Logistiq\Tests\Unit\Utility\Services;

use Prophecy\Prophecy\ObjectProphecy;
use ReliqArts\Contracts\ConfigProvider as ConfigAccessor;
use ReliqArts\Logistiq\Tests\TestCase;
use ReliqArts\Logistiq\Utility\Contracts\ConfigProvider as ConfigProviderContract;
use ReliqArts\Logistiq\Utility\Exceptions\TableNameNotFound;
use ReliqArts\Logistiq\Utility\Services\ConfigProvider;

/**
 * Class ConfigProviderTest.
 *
 * @coversDefaultClass \ReliqArts\Logistiq\Utility\Services\ConfigProvider
 *
 * @internal
 */
final class ConfigProviderTest extends TestCase
{
    /**
     * @var ConfigAccessor|ObjectProphecy
     */
    private $configAccessor;

    /**
     * @var ConfigProviderContract
     */
    private $subject;

    protected function setUp(): void
    {
        $this->configAccessor = $this->prophesize(ConfigAccessor::class);
        $this->subject = new ConfigProvider($this->configAccessor->reveal());
    }

    /**
     * @covers ::getEventsForStatus
     * @dataProvider eventClassDataProvider
     *
     * @param array  $configuredEventClasses
     * @param string ...$expectedResult
     */
    public function testGetEventsForStatus(array $configuredEventClasses, string ...$expectedResult): void
    {
        $statusIdentifier = '1-we23';
        $eventMapKey = 'event_map';

        $this->configAccessor
            ->get(sprintf('%s.%s', $eventMapKey, $statusIdentifier), [])
            ->shouldBeCalledTimes(1)
            ->willReturn($configuredEventClasses);

        $result = $this->subject->getEventsForStatus($statusIdentifier);

        $this->assertSame($result, $expectedResult);
    }

    /**
     * @covers ::__construct
     * @covers ::getTableNameByKey
     *
     * @throws TableNameNotFound
     */
    public function testGetTableNameByKey(): void
    {
        $key = 'ace';
        $tablesKey = 'tables';
        $configuredTableName = 'ace';

        $this->configAccessor
            ->get(sprintf('%s.%s', $tablesKey, $key))
            ->shouldBeCalledTimes(1)
            ->willReturn($configuredTableName);

        $result = $this->subject->getTableNameByKey($key);

        $this->assertSame($configuredTableName, $result);
    }

    /**
     * @covers ::__construct
     * @covers ::getTableNameByKey
     * @covers \ReliqArts\Logistiq\Utility\Exceptions\TableNameNotFound::forKey
     *
     * @throws TableNameNotFound
     */
    public function testGetTableNameByKeyThrowsExceptionOnFailure(): void
    {
        $key = 'ace';
        $tablesKey = 'tables';

        $this->configAccessor
            ->get(sprintf('%s.%s', $tablesKey, $key))
            ->shouldBeCalledTimes(1)
            ->willReturn('');

        $this->expectException(TableNameNotFound::class);
        $this->expectExceptionMessage('Table name for key: `' . $key . '` not found!');

        $this->subject->getTableNameByKey($key);
    }

    /**
     * @return array
     */
    public function eventClassDataProvider(): array
    {
        return [
            'simple' => [
                [
                    'Foo\Bar\Foo',
                ],
                'Foo\Bar\Foo',
            ],
            'complex' => [
                [
                    ' Foo\Bar\Foo  ',
                    'My\Class',
                ],
                'Foo\Bar\Foo',
                'My\Class',
            ],
            'empty' => [
                [],
            ],
        ];
    }
}
