<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentComplaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'category',
        'fee_structure_id',
        'transaction_ref',
        'amount',
        'status',
        'admin_notes',
    ];

    /**
     * Get the student that owns the complaint.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the fee structure associated with the complaint.
     */
    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class);
    }

    /**
     * Get the payment linked to this complaint after verification.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'transaction_ref', 'transaction_ref');
    }
}
