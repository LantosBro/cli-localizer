<?php

namespace CliLocalizer\Tests;

use CliLocalizer\TranslationLoaders\YamlTranslationLoader;
use PHPUnit\Framework\TestCase;

class YamlTranslationLoaderTest extends TestCase
{
	public function testLoad(): void {
		$translations = (new YamlTranslationLoader())->load(__DIR__ . '/fixtures/translations/en_US.yaml');

		$this->assertIsArray($translations);
		$this->assertArrayHasKey('greeting', $translations);
		$this->assertEquals('Hello, :name!', $translations['greeting']);
	}

	public function testLoadInvalidYaml(): void {
		$this->expectException(\RuntimeException::class);

		$loader = new YamlTranslationLoader();
		$loader->load(__DIR__ . '/fixtures/translations/invalid.yaml');
	}
}