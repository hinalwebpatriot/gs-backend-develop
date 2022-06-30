<?php


namespace GSD\Containers\Referral\Jobs;


use GSD\Containers\Subscribe\Managers\ServerManager;
use GSD\Ship\Parents\Jobs\Job;

/**
 * Class SubscribeReferrerJob
 * @package GSD\Containers\Referral\Jobs
 */
class SubscribeReferrerJob extends Job
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ServerManager::subscribeForReferral($this->email);
    }
}