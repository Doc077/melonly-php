<?php

namespace Melonly\Mailing;

class Mailer implements MailerInterface {
    public function send(string $to, string $subject, string $message, bool $wrap = true): void {
        if ($wrap) {
            $message = wordwrap($message, 72);
        }

        $headers = 'From: ' . config('mail.address') . PHP_EOL . 'Reply-To: ' . config('mail.address') . "\r\n";

        mail($to, $subject, $message, $headers);
    }
}
