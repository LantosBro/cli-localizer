<?php

namespace CliLocalizer\TranslationLoaders;

class MoTranslationLoader implements TranslationLoaderInterface
{
	/**
	 * @inheritDoc
	 */
	public function load(string $filePath): array {
		if (!extension_loaded('gettext')) {
			throw new \RuntimeException('Gettext extension is not loaded. Please install the PHP gettext extension.');
		}

		$domain = 'messages';
		$directory = dirname($filePath);
		$locale = basename($filePath, '.mo');

		// Bind the text domain to the directory
		bindtextdomain($domain, $directory);
		// Set the domain
		textdomain($domain);
		// Set the locale
		setlocale(LC_ALL, $locale);

		// Unfortunately, there's no direct way to get all translations from an MO file using PHP's gettext functions.
		// We would need to know all the original strings to get their translations.
		// As a workaround, we'll implement a basic MO file parser.

		return $this->parseMoFile($filePath);
	}

	private function parseMoFile(string $filePath): array {
		$translations = [];

		$fileHandle = fopen($filePath, 'rb');
		if ($fileHandle === false) {
			throw new \RuntimeException("Unable to open MO file: $filePath");
		}

		$header = unpack('V1magic/V1version/V1count/V1o_offset/V1t_offset/V1hash_offset/V1hash_size', fread($fileHandle, 24));

		if ($header['magic'] !== 0x950412de) {
			fclose($fileHandle);
			throw new \RuntimeException("Invalid MO file: $filePath");
		}

		for ($i = 0; $i < $header['count']; $i++) {
			$originalOffset = $header['o_offset'] + $i * 8;
			$translationOffset = $header['t_offset'] + $i * 8;

			fseek($fileHandle, $originalOffset);
			$originalData = unpack('V1length/V1offset', fread($fileHandle, 8));

			fseek($fileHandle, $translationOffset);
			$translationData = unpack('V1length/V1offset', fread($fileHandle, 8));

			fseek($fileHandle, $originalData['offset']);
			$original = fread($fileHandle, $originalData['length']);

			fseek($fileHandle, $translationData['offset']);
			$translation = fread($fileHandle, $translationData['length']);

			if ($original !== '') {
				$translations[$original] = $translation;
			}
		}

		fclose($fileHandle);

		return $translations;
	}
}