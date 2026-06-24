<?php

declare(strict_types=1);

namespace SimiyuSamuel\VscuSdk;

use Illuminate\Support\ServiceProvider;

final class VscuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/vscu.php', 'vscu');

        $this->app->singleton('vscu', function ($app) {
            $config = $app['config']->get('vscu', []);

            return new VscuClient(
                baseUrl: (string) ($config['base_url'] ?? 'http://localhost:8088'),
                timeout: (int) ($config['timeout'] ?? 90),
                headers: is_array($config['headers'] ?? null) ? $config['headers'] : [],
            );
        });

        $this->app->alias('vscu', VscuClient::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/vscu.php' => config_path('vscu.php'),
        ], 'vscu-config');
    }
}
