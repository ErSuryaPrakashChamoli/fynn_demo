<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CustomerJourneyProgressTest extends TestCase
{
    /**
     * @return array<string, array{string, string, string, string}>
     */
    public static function activeJourneyStatuses(): array
    {
        return [
            'sfl' => ['sfl', 'SFL', 'Customer file is currently at SFL stage.', 'width: 0%;'],
            'underwriting' => ['underwriting', 'Underwriting', 'File is under bank / underwriting review.', 'width: 33.333%;'],
            'approved' => ['approved', 'Approved', 'Loan is approved and ready for sanction processing.', 'width: 66.666%;'],
            'sanctioned' => ['sanctioned', 'Sanctioned', 'Loan has been sanctioned successfully.', 'width: 100%;'],
        ];
    }

    #[DataProvider('activeJourneyStatuses')]
    public function test_it_renders_active_journey_progress(string $status, string $label, string $message, string $progressWidth): void
    {
        $html = $this->renderJourneyStatus($status);

        $this->assertStringContainsString($label, $html);
        $this->assertStringContainsString($message, $html);
        $this->assertStringContainsString($progressWidth, $html);
        $this->assertStringNotContainsString('Journey Stopped', $html);
    }

    public function test_it_renders_not_approved_as_stopped(): void
    {
        $html = $this->renderJourneyStatus('not_approved');

        $this->assertStringContainsString('Not Approved', $html);
        $this->assertStringContainsString('Loan was not approved.', $html);
        $this->assertStringContainsString('Journey Stopped', $html);
        $this->assertStringNotContainsString('width: 100%;', $html);
    }

    private function renderJourneyStatus(?string $status): string
    {
        return view('filament.components.customer-journey-progress', [
            'get' => fn (string $key): ?string => $key === 'journey_status' ? $status : null,
        ])->render();
    }
}
