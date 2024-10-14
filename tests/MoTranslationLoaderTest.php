<?php

namespace CliLocalizer\Tests;

use CliLocalizer\TranslationLoaders\MoTranslationLoader;
use PHPUnit\Framework\TestCase;

class MoTranslationLoaderTest extends TestCase
{
	public function testLoad(): void {
		$translations = (new MoTranslationLoader())->load(__DIR__ . '/fixtures/translations/en_US.mo');

		$this->assertIsArray($translations);
		$this->assertArrayHasKey('greeting', $translations);
		$this->assertEquals('Hello, :name!', $translations['greeting']);
	}

	public function testLoadInvalidMo(): void {
		$this->expectException(\RuntimeException::class);

		$loader = new MoTranslationLoader();
		$loader->load(__DIR__ . '/fixtures/translations/invalid.mo');
	}
}