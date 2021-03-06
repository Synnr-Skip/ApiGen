<?php declare(strict_types=1);

namespace ApiGen\Parser\Tests\Elements;

use ApiGen\Contracts\Parser\Elements\ElementExtractorInterface;
use ApiGen\Contracts\Parser\ParserStorageInterface;
use ApiGen\Contracts\Parser\Reflection\ClassReflectionInterface;
use ApiGen\Tests\ContainerFactory;
use PHPUnit\Framework\TestCase;

final class ElementExtractorTest extends TestCase
{
    /**
     * @var ParserStorageInterface
     */
    private $parserStorage;

    /**
     * @var ElementExtractorInterface
     */
    private $elementExtractor;

    protected function setUp(): void
    {
        $container = (new ContainerFactory)->create();

        $this->elementExtractor = $container->getByType(ElementExtractorInterface::class);
        $this->parserStorage = $container->getByType(ParserStorageInterface::class);
    }

    public function testExtractElementsByAnnotation(): void
    {
        $this->parserStorage->setClasses([
            'SomeClass' => $this->createDeprecatedClassReflectionMock()
        ]);

        $deprecatedElements = $this->elementExtractor->extractElementsByAnnotation('deprecated');
        $this->assertCount(1, $deprecatedElements['classes']);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ClassReflectionInterface
     */
    private function createDeprecatedClassReflectionMock()
    {
        $reflectionClassMock = $this->createMock(ClassReflectionInterface::class);
        $reflectionClassMock->method('isDocumented')
            ->willReturn(true);
        $reflectionClassMock->method('getOwnMethods')
            ->willReturn([]);
        $reflectionClassMock->method('hasAnnotation')
            ->with('deprecated')
            ->willReturn(true);

        return $reflectionClassMock;
    }
}
