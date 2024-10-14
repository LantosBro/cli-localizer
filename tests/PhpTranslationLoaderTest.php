<?php

namespace CliLocalizer\Tests;

use CliLocalizer\TranslationLoaders\PhpTranslationLoader;
use PHPUnit\Framework\TestCase;

class PhpTranslationLoaderTest extends TestCase
{
	public function testLoad(): void {
		$translations = (new PhpTranslationLoader())->load(__DIR__ . '/fixtures/translations/en_US.php');

		$this->assertIsArray($translations);
		$this->assertArrayHasKey('greeting', $translations);
		$this->assertEquals('Hello, :name!', $translations['greeting']);
	}

	public function testLoadInvalidPhp(): void {
		$this->expectException(\RuntimeException::class);

		$loader = new PhpTranslationLoader();
		$loader->load(__DIR__ . '/fixtures/translations/invalid.php');
	}
}