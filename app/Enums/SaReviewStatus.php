<?php

namespace App\Enums;

enum SaReviewStatus: string
{
    case CALLOUT_PENDING_DOCS = 'callout_pending_docs';
    case DOCS_SUBMITTED = 'docs_submitted';
    case REVIEW_IN_PROGRESS = 'review_in_progress';
    case CALLOUT_AFTER_REVIEW = 'callout_after_review';
    case INPUT_REVISED = 'input_revised';
    case COMPLIANCE_COMPLETED = 'compliance_completed';
}
