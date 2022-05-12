<?php

class ArticleHelper extends  Phalcon\Mvc\User\Component
{
    public function get ($offset, $limit, $category)
    {
        $client = new GuzzleHttp\Client(['base_uri' => $this->config->application->artikelUri]);
        $response = $client->request('POST', 'article/get', [
            'form_params' => [
                'limit'     => $limit,
                'offset'    => $offset,
                'category'  => $category
            ]
        ]);

        if ($response->getStatusCode() == 200)
        {
            if ($responseData = json_decode($response->getBody()))
            {
                if ($responseData->status == 1)
                {
                    return (array) $responseData->data;
                }
            }
        }

        return [];
    }

    public function detail ($id)
    {
        $client = new GuzzleHttp\Client(['base_uri' => $this->config->application->artikelUri]);
        $response = $client->request('GET', 'article/detail/'.$id);

        if ($response->getStatusCode() == 200)
        {
            if ($responseData = json_decode($response->getBody()))
            {
                if ($responseData->status == 1)
                {
                    return $responseData->data;
                }
            }
        }

        return false;
    }

    public function view ($id)
    {

    }
}