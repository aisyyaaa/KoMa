<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Seller;

class SellerApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    public function __construct(Seller $seller)
    {
        $this->seller = $seller;
    }

    public function build()
    {
        $verificationUrl = url('/seller/registration/verified');

        return $this->subject('Verifikasi Penjual Diterima')
                    ->view('emails.seller_approved')
                    ->with([
                        'seller' => $this->seller,
                        'verificationUrl' => $verificationUrl,
                    ]);
    }
}
