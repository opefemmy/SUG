<?php

namespace App\Services\IdCard;

use App\Models\IdCard;
use App\Models\Student;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class IdCardService
{
    /**
     * Generate a digital ID card for a student.
     */
    public function generateIdCard(Student $student): IdCard
    {
        return IdCard::updateOrCreate(
            ['student_id' => $student->id],
            [
                'card_number' => 'SUG-ID-' . strtoupper(Str::random(8)),
                'issue_date' => now(),
                'expiry_date' => now()->addYear(),
                'status' => 'active',
                'photo_path' => $student->passport_path ?? 'defaults/student-placeholder.png',
            ]
        );
    }

    /**
     * Generate a verification QR code for the ID card.
     */
    public function generateVerificationQr(IdCard $card): string
    {
        // The QR code points to a public verification URL
        $url = route('id-card.verify', ['card_no' => $card->card_number]);
        return QrCode::format('svg')->size(150)->generate($url);
    }

    /**
     * Block an ID card.
     */
    public function blockCard(IdCard $card): void
    {
        $card->update(['status' => 'blocked']);
    }
}
