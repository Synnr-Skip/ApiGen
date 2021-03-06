<?php declare(strict_types=1);

namespace ApiGen\Parser\Tests\Reflection;

use ApiGen\Contracts\Configuration\ConfigurationInterface;
use ApiGen\Contracts\Parser\ParserStorageInterface;
use ApiGen\Contracts\Parser\Reflection\FunctionReflectionInterface;
use ApiGen\Contracts\Parser\Reflection\TokenReflection\ReflectionFactoryInterface;
use ApiGen\Parser\Broker\Backend;
use ApiGen\Parser\Reflection\TokenReflection\ReflectionFactory;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use TokenReflection\Broker;

final class ReflectionFunctionTest extends TestCase
{
    /**
     * @var FunctionReflectionInterface
     */
    private $reflectionFunction;

    protected function setUp(): void
    {
        $backend = new Backend($this->getReflectionFactory());
        $broker = new Broker($backend);
        $broker->processDirectory(__DIR__ . '/ReflectionFunctionSource');

        $this->reflectionFunction = $backend->getFunctions()['getSomeData'];
    }

    public function testIsDocumented(): void
    {
        $this->assertTrue($this->reflectionFunction->isDocumented());
    }

    private function getReflectionFactory(): ReflectionFactoryInterface
    {
        $parserStorageMock = $this->createMock(ParserStorageInterface::class);
        $configurationMock = $this->createMock(ConfigurationInterface::class);
        $configurationMock->method('getVisibilityLevel')
            ->willReturn(ReflectionProperty::IS_PUBLIC);
        return new ReflectionFactory($configurationMock, $parserStorageMock);
    }
}
