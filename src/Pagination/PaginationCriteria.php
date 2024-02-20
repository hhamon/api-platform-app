<?php

declare(strict_types=1);

namespace App\Pagination;

use Assert\Assertion;

final readonly class PaginationCriteria
{
    public const PAGE_SIZE_LIMIT = 200;

    private int $pageNumber;

    private int $pageSize;

    /**
     * @param array{size: int|numeric-string|null, number: int|numeric-string|null} $page
     */
    public static function fromArray(array $page): self
    {
        Assertion::integerish($page['size'] ?? null, 'The `page[size]` parameter is missing.', 'page[size]');
        Assertion::integerish($page['number'] ?? null, 'The `page[number]` parameter is missing.', 'page[number]');

        return new self((int) $page['number'], (int) $page['size']);
    }

    public function __construct(int $pageNumber, int $pageSize)
    {
        Assertion::min(
            $pageNumber,
            1,
            \sprintf('The `page[number]` parameter value `%u` cannot be strictly lower than `1`.', $pageNumber),
            'pageNumber'
        );

        Assertion::min(
            $pageSize,
            1,
            \sprintf('The `page[size]` parameter value `%u` cannot be strictly lower than `1`.', $pageSize),
            'pageSize'
        );

        Assertion::max(
            $pageSize,
            self::PAGE_SIZE_LIMIT,
            \sprintf('The `page[size]` parameter value `%u` cannot exceed `%u`.', $pageSize, self::PAGE_SIZE_LIMIT),
            'pageSize'
        );

        $this->pageSize = $pageSize;
        $this->pageNumber = $pageNumber;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getPageOffset(): int
    {
        return $this->pageSize * ($this->pageNumber - 1);
    }
}
