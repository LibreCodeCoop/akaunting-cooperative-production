<?php

// SPDX-FileCopyrightText: 2026 LibreCode coop and contributors
// SPDX-License-Identifier: AGPL-3.0-or-later

declare(strict_types=1);

namespace Modules\CooperativeProduction\Domain\Preview;

use DomainException;
use Modules\CooperativeProduction\Domain\Calculation\DecimalAllocation;
use Modules\CooperativeProduction\Domain\Calculation\ProductionDistributionPlanner;
use Modules\CooperativeProduction\Domain\Calculation\WeightedAllocator;

/**
 * Deterministic, read-only characterization preview for already classified facts.
 * No source connector, permissions, publication, taxes or approval is implied.
 */
final readonly class CompetencePreview
{
    /**
     * @param list<array<string,mixed>> $eligibleIncomes
     * @param list<array<string,mixed>> $work
     * @return array<string,mixed>
     */
    public function calculate(
        string $workedCompetence,
        string $sourceRevision,
        array $eligibleIncomes,
        array $work,
    ): array {
        if (preg_match('/^[0-9]{4}-(?:0[1-9]|1[0-2])$/D', $workedCompetence) !== 1
            || $sourceRevision === '' || strlen($sourceRevision) > 128) {
            throw new DomainException('Explicit worked competence and source revision are required.');
        }

        $plan = (new ProductionDistributionPlanner(new WeightedAllocator()))->plan($eligibleIncomes, $work);
        $amounts = [];
        foreach ($plan->amounts as $memberId => $amount) {
            $amounts[(string) $memberId] = DecimalAllocation::cents($amount);
        }
        ksort($amounts, SORT_STRING);

        $factsJson = json_encode([
            'competence' => $workedCompetence,
            'revision' => $sourceRevision,
            'incomes' => $eligibleIncomes,
            'work' => $work,
        ], JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES);
        return [
            'state' => 'synthetic_preview_only',
            'worked_competence' => $workedCompetence,
            'source_revision' => $sourceRevision,
            'input_sha256' => hash('sha256', $factsJson),
            'policy' => 'legacy-kernel-characterization-v1',
            'distribution' => [
                'member_amounts_cents' => $amounts,
                'fixed_eligible_cents' => DecimalAllocation::cents($plan->fixedEligible),
                'variable_eligible_cents' => DecimalAllocation::cents($plan->variableEligible),
                'variable_allocated_cents' => DecimalAllocation::cents($plan->variableAllocated),
            ],
            // These values depend on independently validated financial policies.
            'administrative_rate_cents' => null,
            'frra_cents' => null,
            'taxes_cents' => null,
            'capital_cents' => null,
            'net_payable_cents' => null,
            'publishable' => false,
        ];
    }
}
