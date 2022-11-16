<?php

namespace App\Lib\CpsMail;

use DB;
use Mail;
use Carbon\Carbon;
use App\Models\Staff;

class CpsMail
{
    protected $messageForDNSFailed = [
        'subject' => '【Q-BUSINESS】メール配信エラーのお知らせ',
        'body' => "以下のアドレスは、ドメインが存在しないため配信することができませんでした。
    【%s】

管理画面にて、該当するお申込者のメールアドレスのご確認をお願いいたします。
【イベント名】
%s
【セッション名】
%s
【URL】
https://biz.q-pass.jp/login

-----
※本メールは送信専用メールアドレスとなっております。ご返信されても返答できませんので予めご了承ください。",
    ];

    public function createMailMergeField($label, $value, $attributes = [])
    {
        return new CpsMailMergeFiled(array_merge(['label' => $label, 'test_value' => "<テスト：" . $label . ">", 'value' => $value], $attributes));
    }

    public function checkDomain($mail_domain)
    {
        if (empty($mail_domain) ||
            (!strstr(shell_exec('dig ' . $mail_domain . ' mx'), "ANSWER SECTION")
                && !strstr(shell_exec('dig ' . $mail_domain . ' a'), "ANSWER SECTION"))) {
            return false;
        }

        return true;
    }

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
