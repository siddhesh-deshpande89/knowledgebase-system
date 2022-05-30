<?php

declare(strict_types=1);

namespace KnowledgeSystem\Application\Transformers;

use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\PaginatorInterface;

/**
 * A paginator adapter for illuminate/pagination.
 *
 * @author Danny Herran <me@dannyherran.com>
 */
class IlluminatePaginateAdapter implements PaginatorInterface
{
    /**
     * The paginator instance.
     *
     * @var \Illuminate\Contracts\Pagination\Paginator
     */
    protected $paginator;

    /**
     * Create a new illuminate pagination adapter.
     *
     * @param \Illuminate\Contracts\Pagination\Paginator $paginator
     *
     * @return void
     */
    public function __construct(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    public function getCurrentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function getLastPage(): int
    {
        return $this->paginator->lastPage();
    }

    public function getTotal(): int
    {
        return $this->paginator->total();
    }

    public function getCount(): int
    {
        return $this->paginator->count();
    }

    public function getPerPage(): int
    {
        return $this->paginator->perPage();
    }

    public function getUrl(int $page): string
    {
        return $this->paginator->url($page);
    }
}
