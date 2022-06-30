<?php


namespace lenal\subscribe\Jobs;


use HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInput;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use HubSpot\Factory;
use HubSpot\Client\Crm\Contacts\Model\Filter;
use HubSpot\Client\Crm\Contacts\Model\FilterGroup;
use HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest;


class SubscriberToHubspotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;

    /**
     * Create a new job instance.
     *
     * @param  string  $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \HubSpot\Client\Crm\Contacts\ApiException
     */
    public function handle()
    {
        $hubClient = Factory::createWithApiKey(config('catalog.hubspot_api_key'));
        $filter = new Filter();
        $filter->setOperator('EQ')
            ->setPropertyName('email')
            ->setValue($this->email);
        $filterGroup = new FilterGroup();
        $filterGroup->setFilters([$filter]);
        $searchRequest = new PublicObjectSearchRequest();
        $searchRequest->setFilterGroups([$filterGroup]);

        $result = $hubClient->crm()->contacts()->searchApi()->doSearch($searchRequest);
        if ($result->getTotal() == 0) {
            $contactInput = new SimplePublicObjectInput();
            $contactInput->setProperties(['email' => $this->email]);
            $hubClient->crm()->contacts()->basicApi()->create($contactInput);
        }
    }
}
