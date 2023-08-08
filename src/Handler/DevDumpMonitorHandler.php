<?php

namespace Drewdan\DevDumpMonitor\Handler;

use Monolog\Logger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Drewdan\DevDumpMonitor\Dto\LogEntry;
use Monolog\Handler\AbstractProcessingHandler;

class DevDumpMonitorHandler extends AbstractProcessingHandler {

	protected $client;

	public function __construct($level = Logger::DEBUG, bool $bubble = true) {
		parent::__construct($level, $bubble);

		$this->client = App::make(DevDumpMonitorHandler::class);
	}

	protected function write(array $record): void {
		if (Str::startsWith($record['message'], 'Received a payload from client')) {
			return;
		}

		$logEntry = new LogEntry($record);

		if (config('devdump-monitor.user.retrieve')) {
			$logEntry->addUserToLog();
		}

		$this->client->postLog($logEntry);
	}
}
