<?php declare(strict_types=1);

namespace Rector\CodingStyle\Tests\Rector\Namespace_\ImportFullyQualifiedNamesRector;

use Rector\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class ImportFullyQualifiedNamesRectorTest extends AbstractRectorTestCase
{
    public function test(): void
    {
        $this->doTestFiles([
            __DIR__ . '/Fixture/fixture.php.inc',
            __DIR__ . '/Fixture/double_import.php.inc',
            __DIR__ . '/Fixture/double_import_with_existing.php.inc',
            __DIR__ . '/Fixture/already_with_use.php.inc',
            __DIR__ . '/Fixture/already_class_name.php.inc',
            // keep
            __DIR__ . '/Fixture/keep.php.inc',
            __DIR__ . '/Fixture/keep_same_end.php.inc',
            __DIR__ . '/Fixture/keep_trait_use.php.inc',
            // php doc
            __DIR__ . '/Fixture/import_param_doc.php.inc',
            __DIR__ . '/Fixture/import_return_doc.php.inc',
            __DIR__ . '/Fixture/doc_combined.php.inc',
        ]);
    }

    protected function getRectorClass(): string
    {
        return ImportFullyQualifiedNamesRector::class;
    }
}
