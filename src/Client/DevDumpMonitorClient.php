<?php

namespace Drewdan\DevDumpMonitor\Client;

use Illuminate\Support\Facades\Http;
use Drewdan\DevDumpMonitor\Dto\LogEntry;
use Illuminate\Http\Client\PendingRequest;

class DevDumpMonitorClient {
	protected PendingRequest $client;

	protected string $ingressUrl;
	
	protected string $key;

	public function __construct(string $ingressUrl, string $key) {
		$this->ingressUrl = $ingressUrl;
		$this->key = $key;
		$this->client = Http::withToken($this->key)->asJson();
	}

	public function postLog(LogEntry $logEntry): void {
		$exception = $logEntry->exception ? serialize($logEntry->exception) : null;

		$body = [
			'name' => $logEntry->message,
			'level' => $logEntry->type,
			'exception' => $exception,
			'header_data' => collect(request()->headers),
			'ip' => request()->ip(),
			'user' => $logEntry->user,
			'user_id' => (string) $logEntry->user_id,
			'context' => $logEntry->context,
			'lines' => $logEntry->lines,
			'occurred_at' => $logEntry->date->format('Y-m-d H:i:s'),
		];

		try {
			$this->client->timeout(10)->post($this->ingressUrl, $body);
		} catch (\Throwable $e) {

		}
	}
}
