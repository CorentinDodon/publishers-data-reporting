<?php

namespace Http\Controllers;

use App\Models\Publisher;
use Symfony\Component\HttpFoundation\Response ;
use Tests\TestCase;

class ReportingDataControllerTest extends TestCase
{

    public function testGetReportingDataByPublisherByIntervalUri()
    {
        $publisher = Publisher::factory()->hasReportingData(2)->create()->getAttributes();
        $this->json('get', 'api/reporting-data/publisher/' . $publisher['id'] . '/interval/2023-02-08T07:17:27Z/2023-04-18T14:35:31Z?page=1&perPage=20&currency=USD')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'requests',
                            'impressions',
                            'clicks',
                            'revenues',
                            'currency',
                            'report_date',
                            'publisher_id',
                            'publisher' => [
                                'id',
                                'name'
                            ],
                        ]
                    ],
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url',
                    'links' => [
                        '*' => [
                            'url',
                            'label',
                            'active'
                        ]
                    ],
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total'
                ]
            );
    }
}
