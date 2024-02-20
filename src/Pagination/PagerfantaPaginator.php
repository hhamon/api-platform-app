<?php

declare(strict_types=1);

namespace App\Pagination;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @template T
 *
 * @implements PaginatorInterface<T>
 */
class PagerfantaPaginator implements PaginatorInterface
{
    private const PAGE_SIZE_LIMIT = 200;

    /**
     * @return PagerfantaPaginator<T>
     */
    public static function fromQueryBuilder(QueryBuilder $qb, bool $fetchJoinCollection = true): self
    {
        return self::fromAdapter(new QueryAdapter($qb, $fetchJoinCollection));
    }

    /**
     * @param T[] $array
     *
     * @return PagerfantaPaginator<T>
     */
    public static function fromArray(array $array): self
    {
        return self::fromAdapter(new ArrayAdapter($array));
    }

    /**
     * @param Pagerfanta<T> $pagerfanta
     */
    public function __construct(
        private readonly Pagerfanta $pagerfanta,
    ) {
    }

    public function getTotalCount(): int
    {
        return $this->pagerfanta->getNbResults();
    }

    public function getPerPage(): int
    {
        return $this->pagerfanta->getMaxPerPage();
    }

    public function getTotalPages(): int
    {
        return $this->pagerfanta->getNbPages();
    }

    public function getCurrentPage(): int
    {
        return $this->pagerfanta->getCurrentPage();
    }

    /**
     * @return array<int, T>
     */
    public function getResult(): array
    {
        return $this->getTotalCount() >= 1 ? \iterator_to_array($this->getIterator()) : [];
    }

    /**
     * @return \Traversable<int, T>
     */
    public function getIterator(): \Traversable
    {
        return $this->pagerfanta->getIterator();
    }

    public function applyCriteria(PaginationCriteria $criteria): void
    {
        if ($criteria->getPageSize() > self::PAGE_SIZE_LIMIT) {
            throw new BadRequestHttpException(\sprintf('The `page[size]` parameter value `%u` cannot exceed `%s`.', $criteria->getPageSize(), self::PAGE_SIZE_LIMIT));
        }

        $this->pagerfanta->setMaxPerPage($criteria->getPageSize());
        $this->pagerfanta->setCurrentPage(\min($this->pagerfanta->getNbPages(), $criteria->getPageNumber()));
    }

    /**
     * @return array<string, int>
     */
    public function getMeta(): array
    {
        return [
            'totalCount' => $this->getTotalCount(),
            'perPage' => $this->getPerPage(),
            'totalPages' => $this->getTotalPages(),
            'currentPage' => $this->getCurrentPage(),
        ];
    }

    /**
     * @param AdapterInterface<T> $adapter
     *
     * @return PagerfantaPaginator<T>
     */
    private static function fromAdapter(AdapterInterface $adapter): self
    {
        return new self(new Pagerfanta($adapter));
    }
}
