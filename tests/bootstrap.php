<?php
// SPDX-FileCopyrightText: 2026 LibreCode coop and contributors
// SPDX-License-Identifier: AGPL-3.0-or-later
declare(strict_types=1);

spl_autoload_register(static function (string $name): void {
    $prefix = 'Modules\\CooperativeProduction\\';
    if (str_starts_with($name, $prefix)) {
        $path = dirname(__DIR__) . '/' . str_replace('\\', '/', substr($name, strlen($prefix))) . '.php';
        if (is_file($path)) {
            require_once $path;
        }
    }
});
