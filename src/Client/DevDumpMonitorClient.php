<?php

namespace Drewdan\DevDumpMonitor\Client;

use Illuminate\Support\Facades\Http;
use Drewdan\DevDumpMonitor\Dto\LogEntry;
use Illuminate\Http\Client\PendingRequest;

class DevDumpMonitorClient {
	protected PendingRequest $client;

	protected string $ingressUrl;

	protected string $applicationId;

	protected string $key;

	public function __construct(string $ingressUrl, string $applicationId, string $key) {
		$this->ingressUrl = $ingressUrl;
		$this->applicationId = $applicationId;
		$this->key = $key;
		$this->client = Http::withToken($this->key)->asJson();
	}

	public function postLog(LogEntry $logEntry) {
		$exception = $logEntry->exception ? serialize($logEntry->exception) : null;

		$body = [
			'name' => $logEntry->message,
			'type' => $logEntry->type,
			'exception' => $exception,
			'header_data' => collect(request()->headers),
			'ip' => request()->ip(),
			'user' => $logEntry->user,
			'user_id' => $logEntry->user_id,
			'context' => $logEntry->context,
		];

		$this->client->timeout(10)->post($this->ingressUrl, $body);
	}
}
