<?php

// SPDX-FileCopyrightText: 2026 LibreCode coop and contributors
// SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

namespace Modules\CooperativeProduction\Tests\Unit;

use DomainException;
use Modules\CooperativeProduction\Domain\Preview\CompetencePreview;
use PHPUnit\Framework\TestCase;

final class CompetencePreviewTest extends TestCase
{
    public function testSyntheticCompetenceWithTraceAndNoPayablePublication(): void
    {
        $preview = (new CompetencePreview())->calculate(
            '2026-09', 'kimai-synthetic-r1',
            [['customer_reference' => 'customer-a', 'base_producao' => 1.00,
                'percentual_desconto_fixo' => true]],
            [
                ['customer_reference' => 'customer-a', 'tax_number' => 'member-a',
                    'trabalhado' => 3600, 'peso' => 1],
                ['customer_reference' => 'customer-a', 'tax_number' => 'member-b',
                    'trabalhado' => 3600, 'peso' => 1],
            ],
        );
        self::assertSame('synthetic_preview_only', $preview['state']);
        self::assertSame(['member-a' => 50, 'member-b' => 50],
            $preview['distribution']['member_amounts_cents']);
        self::assertSame(100, $preview['distribution']['fixed_eligible_cents']);
        self::assertNull($preview['net_payable_cents']);
        self::assertFalse($preview['publishable']);
        self::assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $preview['input_sha256']);
    }

    public function testNoSilentDefaultForInvalidCompetence(): void
    {
        $this->expectException(DomainException::class);
        (new CompetencePreview())->calculate('2026-13', 'r1', [], []);
    }
}
