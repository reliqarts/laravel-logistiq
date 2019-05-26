<?php

declare(strict_types=1);

namespace ReliqArts\Logistiq\Utility;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use ReliqArts\Logistiq\Utility\Contracts\ConfigProvider as ConfigProviderContract;
use ReliqArts\Logistiq\Utility\Contracts\EventDispatcher as EventDispatcherContract;
use ReliqArts\Logistiq\Utility\Contracts\Logger as LoggerContract;
use ReliqArts\Logistiq\Utility\Services\ConfigProvider;
use ReliqArts\Logistiq\Utility\Services\EventDispatcher;
use ReliqArts\Logistiq\Utility\Services\Logger;
use ReliqArts\Logistiq\Utility\Services\StreamHandler;
use ReliqArts\ServiceProvider as ReliqArtsServiceProvider;
use ReliqArts\Services\ConfigProvider as ReliqArtsConfigProvider;

final class ServiceProvider extends ReliqArtsServiceProvider implements DeferrableProvider
{
    protected const CONFIG_KEY = 'reliqarts-logistiq';
    protected const ASSET_DIRECTORY = __DIR__ . '/../..';
    protected const LOGGER_NAME = self::CONFIG_KEY . '-logger';
    protected const LOG_FILENAME = self::CONFIG_KEY;

    public function boot()
    {
        parent::boot();

        $this->handleMigrations();
    }

    public function provides()
    {
        return [
            ConfigProviderContract::class,
            LoggerContract::class,
            EventDispatcherContract::class,
        ];
    }

    protected function registerBindings(): void
    {
        $this->app->singleton(
            ConfigProviderContract::class,
            function (): ConfigProviderContract {
                return new ConfigProvider(
                    new ReliqArtsConfigProvider(
                        resolve(ConfigRepository::class),
                        $this->getConfigKey()
                    )
                );
            }
        );

        $this->app->singleton(
            LoggerContract::class,
            function (): LoggerContract {
                $logger = new Logger($this->getLoggerName());
                $logFile = storage_path(sprintf('logs/%s.log', $this->getLogFilename()));
                $logger->pushHandler(new StreamHandler($logFile, Logger::DEBUG));

                return $logger;
            }
        );

        $this->app->singleton(
            EventDispatcherContract::class,
            EventDispatcher::class
        );
    }

    private function handleMigrations()
    {
        $this->loadMigrationsFrom(sprintf('%s/database/migrations', $this->getAssetDirectory()));
    }
}
