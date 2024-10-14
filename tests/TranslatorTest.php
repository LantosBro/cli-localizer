<?php

namespace CliLocalizer\Tests;

use CliLocalizer\Translator;
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase
{
	private string $translationsPath;

	protected function setUp(): void
	{
		$this->translationsPath = __DIR__ . '/fixtures/translations';
	}

	public function testTranslateWithPhpLoader(): void {
		$translator = new Translator($this->translationsPath, 'php', 'en_US');
		$this->assertEquals('Hello, World!', $translator->translate('greeting', ['name' => 'World']));
		$this->assertEquals('Processing 5 files...', $translator->translate('processing_files', ['count' => 5]));
	}

	public function testTranslateWithJsonLoader(): void {
		$translator = new Translator($this->translationsPath, 'json', 'en_US');
		$this->assertEquals('Hello, World!', $translator->translate('greeting', ['name' => 'World']));
		$this->assertEquals('Processing 5 files...', $translator->translate('processing_files', ['count' => 5]));
	}

	public function testTranslateWithYamlLoader(): void {
		$translator = new Translator($this->translationsPath, 'yaml', 'en_US');
		$this->assertEquals('Hello, World!', $translator->translate('greeting', ['name' => 'World']));
		$this->assertEquals('Processing 5 files...', $translator->translate('processing_files', ['count' => 5]));
	}

	public function testTranslateWithTomlLoader(): void {
		$translator = new Translator($this->translationsPath, 'toml', 'en_US');
		$this->assertEquals('Hello, World!', $translator->translate('greeting', ['name' => 'World']));
		$this->assertEquals('Processing 5 files...', $translator->translate('processing_files', ['count' => 5]));
	}

	public function testTranslateWithMoLoader(): void {
		$translator = new Translator($this->translationsPath, 'mo', 'en_US');
		$this->assertEquals('Hello, World!', $translator->translate('greeting', ['name' => 'World']));
		$this->assertEquals('Processing 5 files...', $translator->translate('processing_files', ['count' => 5]));
	}

	public function testFallbackToDefaultLocale(): void {
		$translator = new Translator($this->translationsPath, 'php', 'fr_FR', 'en_US');
		$this->assertEquals('Hello, World!', $translator->translate('greeting', ['name' => 'World']));
	}

	public function testNonExistentKey(): void {
		$translator = new Translator($this->translationsPath, 'php', 'en_US');
		$this->assertEquals('non_existent_key', $translator->translate('non_existent_key'));
	}
}