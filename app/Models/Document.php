<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_received',
        'document_no',
        'document_type',
        'particulars',
        'oed_received',
        'oed_date_received',
        'oed_status',
        'oed_remarks',
        'records_received',
        'records_date_received',
        'records_remarks',
        'under_review_at',
        'in_progress_at',
        'for_release_at',
        'returned_at',
        'completed_at',
        'forwarded_to_oed',
        'forwarded_to_records',
    ];

    protected $casts = [
        'date_received' => 'datetime',
        'oed_date_received' => 'datetime',
        'records_date_received' => 'datetime',
        'under_review_at' => 'datetime',
        'in_progress_at' => 'datetime',
        'for_release_at' => 'datetime',
        'returned_at' => 'datetime',
        'completed_at' => 'datetime',
        'forwarded_to_oed' => 'datetime',
        'forwarded_to_records' => 'datetime',
    ];

    public function logs()
    {
        return $this->hasMany(DocumentLog::class);
    }
}
