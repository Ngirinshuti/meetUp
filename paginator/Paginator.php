<?php 
// query paginatorr

require_once __DIR__ . "/../classes/Session.php";


class Paginator {
    protected string $name = "";
    public bool $has_more = true;
    protected int $start = 0;
    protected int $length = 10;

    public function setLength(int $length):Paginator
    {
        $this->length = $length;
        return $this;
    }

    public function hasPrev():bool
    {
        // var_dump($this->start, $this->length);
        return $this->start >= $this->length;
    }

    public function getCurrentPage():int
    {
        return floor($this->start / $this->length) + 1;
    }

    public function updateStart():Paginator
    {
        if (isset($_GET['page'])){
            return $this;
        }

        $this->start += $this->length;
        
        return $this;
    }

    public function updateHasMore(int $data_length):Paginator
    {
        if ($data_length < $this->length) {
            $this->has_more = false;
        }

        $page = isset($_GET['page']) ? $_GET["page"] : null;

        if ($page) {
            $this->start = $this->length * (intval($page) - 1);
        }

        return $this;
    }

    public function getLimitString():string
    {
        $page = isset($_GET['page']) ? $_GET["page"] : null;

        if ($page) {
            $this->start = $this->length * (intval($page) - 1);
        }

        return " LIMIT {$this->length} OFFSET {$this->start}";
    }

}
