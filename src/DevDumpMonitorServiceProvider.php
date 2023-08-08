<?php

namespace Drewdan\DevDumpMonitor;

use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Drewdan\DevDumpMonitor\Client\DevDumpMonitorClient;
use Drewdan\DevDumpMonitor\Handler\DevDumpMonitorHandler;

class DevDumpMonitorServiceProvider extends ServiceProvider {

	public function register(): void {
		$this->mergeConfigFrom(__DIR__ . '/../config/devdump-monitor.php', 'devdump-monitor');
		$this->registerLogHandler();

		$this->app->singleton(DevDumpMonitorClient::class, function ($app) {
			return new DevDumpMonitorClient(
				config('devdump-monitor.ingress_url'),
				config('devdump-monitor.key')
			);
		});
	}

	public function boot() {

	}

	protected function registerLogHandler(): void {
		$this->app->singleton('devdump.logger', function ($app) {
			$handler = new DevDumpMonitorHandler();

			return tap(
				new Logger('DevDumpMonitor'),
				fn (Logger $logger) => $logger->pushHandler($handler)
			);
		});

		Log::extend('devdump', fn ($app) => $app['devdump.logger']);
	}

}
