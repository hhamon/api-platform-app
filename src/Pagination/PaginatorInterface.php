<?php

declare(strict_types=1);

namespace App\Pagination;

use IteratorAggregate;

/**
 * @template T
 *
 * @extends IteratorAggregate<T>
 */
interface PaginatorInterface extends \IteratorAggregate
{
    /**
     * @phpstan-return int<0, max>
     */
    public function getTotalCount(): int;

    /**
     * @phpstan-return int<1, max>
     */
    public function getPerPage(): int;

    /**
     * @phpstan-return int<1, max>
     */
    public function getTotalPages(): int;

    /**
     * @phpstan-return int<1, max>
     */
    public function getCurrentPage(): int;

    /**
     * @return T[]
     */
    public function getResult(): array;

    public function applyCriteria(PaginationCriteria $criteria): void;
}
