<?php

namespace CliLocalizer\TranslationLoaders;

class YamlTranslationLoader implements TranslationLoaderInterface
{
	/**
	 * @inheritDoc
	 */
	public function load(string $filePath): array {
		if (!extension_loaded('yaml')) {
			throw new \RuntimeException('YAML extension is not loaded. Please install the PHP YAML extension.');
		}

		$translations = yaml_parse_file($filePath);

		if ($translations === false) {
			throw new \RuntimeException("Failed to parse YAML file: $filePath");
		}

		return $translations ?? [];
	}
}