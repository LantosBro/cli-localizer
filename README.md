# CliLocalizer

CliLocalizer is a PHP library for easy localization in CLI applications with support for multiple translation file formats (JSON, YAML, PHP(Array) and MO).

## Installation

You can install the package via composer:

```bash
composer require lantosbro/cli-localizer
```

### Requirements

- PHP 7.4 or higher
- PHP YAML extension
- PHP Gettext extension

To install the YAML extension:

```bash
pecl install yaml
```

To install the Gettext extension:

```bash
pecl install gettext
```

Then add `extension=yaml.so` and `extension=gettext.so` to your php.ini file.

## Usage

```php
use CliLocalizer\Translator;
use CliLocalizer\CliOutput;

// Initialize the Translator with the required translations path, format, and optional locale and fallback locale
$translator = new Translator('/path/to/your/translations', 'mo', 'ru_RU', 'en_US');

// Create a CliOutput instance
$output = new CliOutput($translator);

// Use the output
$output->writeln('welcome_message', ['name' => 'User']);
$output->writeln('processing_files', ['count' => 5]);
```

## Configuration

You must specify the path to your translations directory and the format of your translation files when initializing the Translator. Supported formats are:

- JSON ('json')
- YAML ('yaml')
- PHP ('php')
- MO ('mo')

Create translation files in your specified translations directory using the chosen format.

Example translation files:

JSON (en_US.json):
```json
{
    "welcome_message": "Welcome, :name!",
    "processing_files": "Processing :count files..."
}
```

YAML (en_US.yaml):
```yaml
welcome_message: "Welcome, :name!"
processing_files: "Processing :count files..."
```

PHP (en_US.php):
```php
<?php
return [
    'welcome_message' => 'Welcome, :name!',
    'processing_files' => 'Processing :count files...',
];
```

For MO files, you need to compile them from PO files using tools like `msgfmt`. The structure should be similar to other formats, with keys being the original strings and values being the translations.

The Translator will attempt to load translations in the following order:
1. The specified locale (e.g., 'ru_RU')
2. The language code of the specified locale (e.g., 'ru')
3. The fallback locale (default is 'en_US')

Ensure that you have at least a translation file for your fallback locale.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.