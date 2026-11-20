<?php

// SPDX-FileCopyrightText: 2026 LibreCode coop and contributors
// SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

require dirname(__DIR__) . '/tests/bootstrap.php';

use Modules\CooperativeProduction\Domain\Preview\CompetencePreview;

$preview = (new CompetencePreview())->calculate(
    '2026-09',
    'synthetic-revision-1',
    [['customer_reference' => 'synthetic-client', 'base_producao' => '1.00',
        'percentual_desconto_fixo' => true]],
    [
        ['customer_reference' => 'synthetic-client', 'tax_number' => 'synthetic-member-a',
            'trabalhado' => 3600, 'peso' => 1],
        ['customer_reference' => 'synthetic-client', 'tax_number' => 'synthetic-member-b',
            'trabalhado' => 3600, 'peso' => 1],
    ],
);

echo json_encode($preview, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES) . PHP_EOL;
