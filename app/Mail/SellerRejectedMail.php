<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Seller;

class SellerRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;
    public $reason;

    public function __construct(Seller $seller, $reason = null)
    {
        $this->seller = $seller;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Verifikasi Penjual Ditolak')
                    ->view('emails.seller_rejected')
                    ->with(['seller' => $this->seller, 'reason' => $this->reason]);
    }
}
