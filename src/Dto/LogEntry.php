<?php

namespace Drewdan\DevDumpMonitor\Dto;

use Illuminate\Support\Arr;

class LogEntry {

	public string $message;

	public string $type;

	public $date;

	public $stack;

	public $exception;

	public $user_id;

	public ?string $user;

	public array $context;

	public function __construct(array $data) {
		$this->user = null;
		$this->message = $data['message'];
		$this->type = ucfirst(strtolower($data['level_name']));
		$this->date = $data['datetime'];
		$this->exception = Arr::has($data, 'context.exception') ? $data['context']['exception'] : null;
		$this->user_id = Arr::has($data, 'context.user_id') ? $data['context']['user_id'] : null;
		$this->context = Arr::has($data, 'context') ? Arr::except($data, 'context.exception') : [];
	}

	public function addUserToLog() {
		if (!config('devdump-monitor.user.retrieve')) {
			return null;
		}

		$userModel = config('devdump-monitor.user.model');
		$attribute = config('devdump-monitor.user.identifier');

		$this->user = $userModel::find($this->user_id)->$attribute;
	}
}
