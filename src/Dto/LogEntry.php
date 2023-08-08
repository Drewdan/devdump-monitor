<?php

namespace Drewdan\DevDumpMonitor\Dto;

use Monolog\LogRecord;
use DateTimeImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class LogEntry {

	public string $message;

	public string $type;

	public DateTimeImmutable $date;

	public $stack;

	public $exception;

	public $user_id;

	public ?string $user;
	public array $lines = [];

	public array $context;

	public function __construct(LogRecord $log) {
		$this->user = null;
		$this->message = $log->message;
		$this->type = $log->level->getName();
		$this->date = $log->datetime;
		$this->exception = Arr::has($log->context, 'exception') ? $log->context['exception'] : null;

		if ($this->exception instanceof \Throwable) {
			$file = $this->exception->getFile();
			$line = $this->exception->getLine();

			if (is_file($file)) {
				$allLines = file($file);
				$startLine = max(0, $line - 11);
				$endLine = min(count($allLines) - 1, $line + 10);

				for ($i = $startLine; $i <= $endLine; $i++) {
					$this->lines[$i + 1] = trim($allLines[$i]);
				}
			}
		}

		$this->user_id = Auth::check() ? Auth::id() : null;
		$this->context = $log->context;
	}

	public function addUserToLog(): void {
		$userModel = config('devdump-monitor.user.model');
		$attribute = config('devdump-monitor.user.identifier');

		$this->user = $userModel::find($this->user_id)->$attribute;
	}
}
