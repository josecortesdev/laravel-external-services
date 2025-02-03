<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([env('ELASTICSEARCH_HOSTS')])
            ->setElasticMetaHeader(false) // Deshabilitar el encabezado meta de Elasticsearch
            ->build();
    }

    public function search($params)
    {
        return $this->client->search($params);
    }

    public function index($params)
    {
        return $this->client->index($params);
    }
}
