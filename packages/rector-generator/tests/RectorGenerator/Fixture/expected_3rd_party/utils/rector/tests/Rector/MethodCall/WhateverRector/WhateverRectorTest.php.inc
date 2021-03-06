<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\MethodCall\WhateverRector;

use Rector\Core\Testing\PHPUnit\AbstractRectorTestCase;

final class WhateverRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo): void
    {
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    /**
     * @return mixed[]
     */
    protected function getRectorsWithConfiguration(): array
    {
        return [
            \Utils\Rector\Rector\MethodCall\WhateverRector::class =>
                [\Utils\Rector\Rector\MethodCall\WhateverRector::CLASS_TYPE_TO_METHOD_NAME => ['SomeClass' => 'configure']]
        ];
    }
}
