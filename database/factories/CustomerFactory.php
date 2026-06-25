<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{

  protected $model = Customer::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       $eligibilityStatus = fake()->randomElement(['eligible', 'not_eligible']);
        $journeyStatus = fake()->randomElement([
            'sfl',
            'underwriting',
            'approved',
            'not_approved',
            'sanctioned',
        ]);

        $eligibilityReason = null;
        if ($eligibilityStatus === 'not_eligible') {
            $eligibilityReason = fake()->randomElement([
                'company_not_listed',
                'cibil_score',
                'defaulter_bounces',
                'no_residence_proof',
                'low_salary',
                'location_issue',
            ]);
        }

        $journeyNotApprovedReason = null;
        if ($journeyStatus === 'not_approved') {
            $journeyNotApprovedReason = fake()->randomElement([
                'cibil_score',
                'defaulter_bounces',
                'no_residence_proof',
                'low_salary',
                'location_issue',
            ]);
        }

        $sanctionedBank = null;
        $sanctionedLoanAmount = null;
        $cashback = null;
        $subvention = null;
        $payoutRate = null;
        $bankCondition = null;
        $attachmentRequired = null;

        if ($journeyStatus === 'sanctioned') {
            $sanctionedBank = fake()->randomElement([
                'HDFC Bank',
                'ICICI Bank',
                'Axis Bank',
                'SBI',
                'Kotak Mahindra Bank',
            ]);

            $sanctionedLoanAmount = fake()->numberBetween(100000, 1500000);
            $cashback = fake()->numberBetween(0, 50000);
            $subvention = fake()->numberBetween(0, 25000);
            $payoutRate = fake()->randomFloat(2, 1, 15);
            $bankCondition = fake()->sentence();
            $attachmentRequired = fake()->randomElement(['yes', 'no']);
        }

        return [
            'customer_name' => fake()->name(),
            'mobile_no' => fake()->numerify('9#########'),
            'email' => fake()->unique()->safeEmail(),
            'pan_number' => strtoupper(fake()->bothify('?????#####?')),
            'job_location' => fake()->city(),
            'residence_location' => fake()->city(),
            'salary' => fake()->numberBetween(15000, 150000),
            'current_location' => fake()->city(),
            'company_category' => fake()->randomElement([
                'IT',
                'Manufacturing',
                'Finance',
                'Retail',
                'Healthcare',
            ]),
            'bank_eligible_for' => fake()->randomElement([
                'HDFC',
                'ICICI',
                'Axis',
                'SBI',
                'Kotak',
            ]),
            'loan_applied' => fake()->randomElement([
                'Personal Loan',
                'Home Loan',
                'Business Loan',
                'Car Loan',
            ]),
            'eligibility_status' => $eligibilityStatus,
            'eligibility_reason' => $eligibilityReason,
            'journey_status' => $journeyStatus,
            'journey_not_approved_reason' => $journeyNotApprovedReason,
            'sanctioned_bank' => $sanctionedBank,
            'sanctioned_loan_amount' => $sanctionedLoanAmount,
            'cashback' => $cashback,
            'subvention' => $subvention,
            'payout_rate' => $payoutRate,
            'bank_condition' => $bankCondition,
            'attachment_required' => $attachmentRequired,
        ];
    }
}
