<?php

namespace Pegas\Mongo;

use MongoCursor;

class MongoPaginator
{
    private $page;
    private $pageSize;
    private $period;

    private $count;
    private $lastPage;

    public function __construct($page, $pageSize, $period = 5)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->period = $period;
    }

    public function paginate(MongoCursor $cursor)
    {
        if ($this->page < 1) {
            return false;
        }

        $cursor->skip($this->pageSize * ($this->page - 1))->limit($this->pageSize);

        $this->count = $cursor->count();
        $this->lastPage = ceil($this->count / $this->pageSize);

        return $this->page <= $this->lastPage;
    }

    public function currentPage()
    {
        return $this->page;
    }

    public function hasPages()
    {
        return $this->lastPage > 1;
    }

    public function pages()
    {
        $margin = ceil(($this->period - 1) / 2);

        $start = $this->page - $margin;
        if ($start <= 0) {
            $start = 1;
        }

        $end = $start + $this->period - 1;
        if ($end > $this->lastPage) {
            $end = $this->lastPage;
        }

        if ($end - $start < $this->period && $start > 1) {
            $start = $end - $this->period + 1;
        }

        return range($start, $end);
    }

    public function hasPrevious()
    {
        return $this->page > 1;
    }

    public function previous()
    {
        return $this->page - 1;
    }

    public function hasNext()
    {
        return $this->page < $this->lastPage;
    }

    public function next()
    {
        return $this->page + 1;
    }
}
