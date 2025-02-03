<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ElasticsearchService;

class ElasticsearchController extends Controller
{
    protected $elasticsearchService;

    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
    }

    public function index(Request $request)
    {
        $params = [
            'index' => 'my_index',
            'id'    => $request->id,
            'body'  => [
                'title' => $request->title,
                'content' => $request->content,
            ]
        ];

        \Log::info('Indexing document:', ['params' => $params]); // Log para verificar los datos indexados

        // $response = $this->elasticsearchService->index($params);

        // return response()->json($response);

        try {
            $response = $this->elasticsearchService->index($params);
    
            if (isset($response['result']) && $response['result'] == 'created') {
                return response()->json(['success' => true, 'message' => 'Document indexed successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to index document'], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error indexing document:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'An error occurred while indexing the document'], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->query('q');
        \Log::info('Search query:', ['query' => $query]); // Log para verificar la consulta

        if (empty($query)) {
            \Log::info('No search query provided'); // Log para verificar el condicional
            return response()->json(['error' => 'No search query provided'], 400);
        }

        $params = [
            'index' => 'my_index',
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'query' => $query,
                        'fields' => ['title', 'content']
                    ]
                ]
            ]
        ];

        $response = $this->elasticsearchService->search($params);
        \Log::info('Search response:', ['response' => $response->asString()]); // Log para verificar la respuesta

        return response($response->asString(), 200, ['Content-Type' => 'application/json']); // Devolver la respuesta como JSON
    }
}
