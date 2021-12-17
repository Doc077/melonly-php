<?php

namespace Melonly\Mailing;

class Mail {
    public static function send(string $to, string $subject, string $message): void {
        $message = wordwrap($message, 68);

        $headers = 'From: ' . env('MAIL_ADDRESS', 'melonly@email.com') . "\r\n" . 'Reply-To: ' . env('MAIL_ADDRESS', 'melonly@email.com') . "\r\n";

        mail($to, $subject, $message, $headers);
    }
}
