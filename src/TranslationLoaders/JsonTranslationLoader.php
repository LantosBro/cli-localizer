<?php

namespace CliLocalizer\TranslationLoaders;

class JsonTranslationLoader implements TranslationLoaderInterface
{
	/**
	 * @inheritDoc
	 * @throws \JsonException
	 */
	public function load(string $filePath): array {
		$content = file_get_contents($filePath);
		$translations = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new \RuntimeException("Failed to parse JSON file: " . json_last_error_msg());
		}

		return $translations ?? [];
	}
}