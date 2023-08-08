<?php

use Monolog\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Drewdan\DevDumpMonitor\Client\DevDumpMonitorClient;
use Drewdan\DevDumpMonitor\Handler\DevDumpMonitorHandler;

class DevDumpMonitorServiceProvider extends ServiceProvider {

	public function register() {
		$this->mergeConfigFrom(__DIR__ . '/../config/devdump-monitor.php', 'devdump-monitor');

		$this->registerLogger();

		$this->app->singleton('dev-dump', function ($app) {
			return new DevDumpMonitorClient();
		});

	}

	public function boot() {

	}

	public function registerLogger() {
		$this->app->singleton('dev-dump.logger', function ($app) {
			//this should be the custom handler extending the abstract processing handler
			$handler = new DevDumpMonitorHandler;
//
//            $logLevelString = config('logging.channels.flare.level', 'error');
//
//            $logLevel = $this->getLogLevel($logLevelString);
//
//            $handler->setMinimumReportLogLevel($logLevel);

			$logger = new Logger('Sentinel');
			$logger->pushHandler($handler);

			return $logger;
		});

		Log::extend('sentinel', function ($app) {
			return $app['dev-dump.logger'];
		});
	}

}
