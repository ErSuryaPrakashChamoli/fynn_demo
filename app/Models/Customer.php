<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'customer_name',
        'mobile_no',
        'email',
        'pan_number',
        'job_location',
        'residence_location',
        'salary',
        'current_location',
        'company_category',
        'bank_eligible_for',
        'loan_applied',
        'eligibility_status',
        'eligibility_reason',
        'journey_status',
        'journey_not_approved_reason',
        'sanctioned_bank',
        'sanctioned_loan_amount',
        'cashback',
        'subvention',
        'payout_rate',
        'bank_condition',
        'attachment_required',
        'attachment_file',
        'other_bank_eligible_for',
        'application_no',
        'lan_no',
        'documentation_status',
        'pending_document',
        'sfl_remarks',
        'underwriting_remarks',
        'approved_remarks',
        'sanctioned_remarks',
        'not_approved_remarks',
    ];
}
