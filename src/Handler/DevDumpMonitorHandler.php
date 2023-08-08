<?php

namespace Drewdan\DevDumpMonitor\Handler;

use Monolog\Logger;
use Monolog\LogRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Drewdan\DevDumpMonitor\Dto\LogEntry;
use Monolog\Handler\AbstractProcessingHandler;
use Drewdan\DevDumpMonitor\Client\DevDumpMonitorClient;

class DevDumpMonitorHandler extends AbstractProcessingHandler {

	protected function write(LogRecord $record): void {
		if (Str::startsWith($record['message'], 'Received a payload from client')) {
			return;
		}

		$logEntry = new LogEntry($record);

		// TODO: Wrap this in a config item
		$logEntry->addUserToLog();

		App::make(DevDumpMonitorClient::class)->postLog($logEntry);
	}
}
