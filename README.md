# Dev Dump Monitor Service

## Description

This package is designed to be used with the DevDump.online Laravel Log tool. It sends your logs to DevDump, so they can
be reviewed and monitored alongside your other dumps.

## Installation

```bash
composer require drewdan/devdump-laravel-log-monitor
```

## Usage

Add the following to your `config/logging.php` file:

```php
'devdump' => [
    'driver' => 'devdump',
],
```

Then add the following to your `.env` file:

```dotenv
DEVDUMP_INGRESS_URL=your-ingress-url-here
DEVDUMP_KEY=your-key-here
```

The ingress url and key can be generated from within the devdump.online application.

Finally, in your ENV file, change the `LOG_CHANNEL` to `devdump`:

```dotenv
LOG_CHANNEL=devdump
```
