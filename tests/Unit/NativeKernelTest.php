<?php

// SPDX-FileCopyrightText: 2026 LibreCode coop and contributors
// SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/Domain/Calculation/DecimalAllocation.php';
require_once dirname(__DIR__, 2) . '/Domain/Calculation/WeightedAllocator.php';
require_once dirname(__DIR__, 2) . '/Domain/Calculation/ProductionDistributionPlan.php';
require_once dirname(__DIR__, 2) . '/Domain/Calculation/ProductionDistributionPlanner.php';

use Modules\CooperativeProduction\Domain\Calculation\DecimalAllocation;
use Modules\CooperativeProduction\Domain\Calculation\ProductionDistributionPlanner;
use Modules\CooperativeProduction\Domain\Calculation\WeightedAllocator;
use PHPUnit\Framework\TestCase;

final class NativeKernelTest extends TestCase
{
    public function testHalfAwayFromZeroIncludingNegativeCents(): void
    {
        self::assertSame(101, DecimalAllocation::cents('1.005'));
        self::assertSame(-101, DecimalAllocation::cents('-1.005'));
        self::assertSame(100, DecimalAllocation::cents('1.004'));
        self::assertSame(0, DecimalAllocation::cents('0.004'));
    }

    public function testCentExactRemaindersAndStableMemberOrder(): void
    {
        $allocator = new DecimalAllocation();
        $result = $allocator->allocate('1.00', ['gamma' => 1, 'beta' => 1, 'alpha' => 1]);
        ksort($result);
        self::assertSame(['alpha' => 0.34, 'beta' => 0.33, 'gamma' => 0.33], $result);
    }

    public function testInvalidOrMissingMemberIdentityFailsClosed(): void
    {
        $this->expectException(DomainException::class);
        (new WeightedAllocator())->weightsFromRows([
            ['tax_number' => '', 'trabalhado' => 3600, 'peso' => 1],
        ], 'synthetic');
    }

    public function testPlannerCannotDistributeWithoutWorkEvidence(): void
    {
        $this->expectException(DomainException::class);
        (new ProductionDistributionPlanner(new WeightedAllocator()))->plan(
            [['customer_reference' => 'customer-a', 'base_producao' => 100, 'percentual_desconto_fixo' => true]],
            [],
        );
    }
}
