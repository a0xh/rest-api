<?php declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final class Paginator extends LengthAwarePaginator
{
    private readonly private(set) Request $request;

    public function __construct(array $items, int $perPage = 11)
    {
        $this->request = new Request();

        parent::__construct(
            items: $this->items(items: $items, perPage: $perPage),
            total: count(value: $items),
            perPage: $perPage,
            currentPage: $this->request->get(
                key: 'page',
                default: 1
            ),
            options: [
                'path' => $this->request->url(),
                'query' => $this->request->query()
            ]
        );
    }

    private function items(array $items, int $perPage): array
    {
        $currentPage = $this->request->get(key: 'page', default: 1);

        return collect(value: $items)->slice(
            offset: ($currentPage - 1) * $perPage,
            length: $perPage
        )->all();
    }
}
