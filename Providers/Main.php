<?php

// SPDX-FileCopyrightText: 2026 LibreCode coop and contributors
// SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

namespace Modules\CooperativeProduction\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Read-only native foundation. Financial publication must be explicitly implemented
 * and independently authorized; no individual portal is registered here.
 */
final class Main extends ServiceProvider
{
    public function register(): void
    {
        // The pure economic kernel is Composer-autoloadable and has no side effects.
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'cooperative-production');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
