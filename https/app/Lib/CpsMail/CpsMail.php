<?php

namespace App\Lib\CpsMail;

use Mail;

class CpsMail
{
    protected function doSendMail($from, $to, $bcc, $subject, $body)
    {
        try {
            return Mail::raw($body, function ($mail) use ($to, $from, $bcc, $subject) {
                $mail->subject($subject)->from($from);
                $mail->to($to);
                if ($bcc) {
                    $mail->bcc($bcc);
                }
            });
        } catch (\Swift_RfcComplianceException $e) {
            Mail::to(config("error_mail.to_mail_address"))
                ->queue(new ExceptionNotify($e));
        }
    }

    public function mailTo($to, $subject, $body, $bcc = null, $from = null)
    {
        return $this->doSendMail($from ?: "no-reply@q-pass.jp", $to, $bcc, $subject, $body);
    }
}
