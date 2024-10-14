<?php

namespace CliLocalizer\Tests;

use CliLocalizer\TranslationLoaders\JsonTranslationLoader;
use PHPUnit\Framework\TestCase;

class JsonTranslationLoaderTest extends TestCase
{
	public function testLoad(): void {
		$translations = (new JsonTranslationLoader())->load(__DIR__ . '/fixtures/translations/en_US.json');

		$this->assertIsArray($translations);
		$this->assertArrayHasKey('greeting', $translations);
		$this->assertEquals('Hello, :name!', $translations['greeting']);
	}

	public function testLoadInvalidJson(): void {
		$this->expectException(\RuntimeException::class);

		$loader = new JsonTranslationLoader();
		$loader->load(__DIR__ . '/fixtures/translations/invalid.json');
	}
}