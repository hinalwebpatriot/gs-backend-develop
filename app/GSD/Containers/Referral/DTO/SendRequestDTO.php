<?php


namespace GSD\Containers\Referral\DTO;


use GSD\Containers\Referral\UI\API\Requests\SendRequest;
use GSD\Ship\Parents\DTO\DTO;

/**
 * Class SendRequestDTO
 * @package GSD\Containers\Referral\DTO
 */
class SendRequestDTO extends DTO
{
    public string $sender;
    public string $sender_first_name;
    public string $sender_last_name;
    /** @var array|RecipientDTO[] */
    public array   $recipients;
    public ?string $comment;
    public bool    $subscribe;

    public static function fromRequest(SendRequest $request): SendRequestDTO
    {
        $recipients = [];
        foreach ($request->recipients as $recipient) {
            $recipients[] = new RecipientDTO($recipient);
        }
        return new static([
            'sender'            => $request->sender,
            'sender_first_name' => $request->sender_first_name,
            'sender_last_name'  => $request->sender_last_name,
            'recipients'        => $recipients,
            'comment'           => $request->comment,
            'subscribe'         => $request->subscribe,
        ]);
    }
}