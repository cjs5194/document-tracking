<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'changed_by',
        'type',
        'status',
    ];

    // Optional: link back to document
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
