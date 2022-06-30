<?php


namespace GSD\Ship\Parents\Mails;


use GSD\Core\Abstracts\Mails\Mail as CoreMail;
use Illuminate\Queue\SerializesModels;

class Mail extends CoreMail
{
    use SerializesModels;
}