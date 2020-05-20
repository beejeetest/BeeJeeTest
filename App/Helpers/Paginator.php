<?php

namespace App\Helpers;

class Paginator
{

    /**
     * set the number of items per page.
     *
     * @var int
     */
    private $_perPage;

    /**
     * set get parameter for fetching the page number
     *
     * @var string
     */
    private $_instance;

    /**
     * sets the page number.
     *
     * @var int
     */
    private $_page;

    /**
     * set the limit for the data source
     *
     * @var string
     */
    private $_limit;

    /**
     * set the total number of records/items.
     *
     * @var int
     */
    private $_totalRows = 0;

    /**
     * set custom css classes for additional flexibility
     *
     * @var string
     */
    private $_customCSS;


    /**
     * Paginator constructor.
     * @param $perPage
     * @param $instance
     * @param string $customCSS
     */
    public function __construct($perPage, $instance, $customCSS = '')
    {
        $this->_instance = $instance;
        $this->_perPage = $perPage;
        $this->set_instance();
        $this->_customCSS = $customCSS;
    }

    /**
     * get_start
     *
     * creates the starting point for limiting the dataset
     * @return int
     */
    public function get_start()
    {
        return ($this->_page * $this->_perPage) - $this->_perPage;
    }

    /**
     * set_instance
     *
     * sets the instance parameter, if int value is 0 then set to 1
     *
     * @var int
     */
    private function set_instance()
    {
        $this->_page = (int)(!isset($_GET[$this->_instance]) ? 1 : $_GET[$this->_instance]);
        $this->_page = ($this->_page == 0 ? 1 : $this->_page < 0 ? 1 : $this->_page);
    }

    /**
     * set_total
     *
     * collect a numberic value and assigns it to the totalRows
     *
     * @var int
     */
    public function set_total($_totalRows)
    {
        $this->_totalRows = $_totalRows;
    }

    /**
     * get_limit
     *
     * returns the limit for the data source, calling the get_start method and passing in the number of items perp page
     *
     * @return string
     */
    public function get_limit()
    {
        return "LIMIT " . $this->get_start() . ",$this->_perPage";
    }

    /**
     * @return array
     */
    public function get_limit_keys()
    {
        return ['offset' => $this->get_start(), 'limit' => $this->_perPage];
    }

    /**
     * @param string $path
     * @param null $ext
     * @return string
     */
    public function page_links($path = '?', $ext = null)
    {
        $adjacents = "2";
        $prev = $this->_page - 1;
        $next = $this->_page + 1;
        $lastpage = ceil($this->_totalRows / $this->_perPage);
        $lpm1 = $lastpage - 1;

        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<ul class='pagination " . $this->_customCSS . "'>";
            if ($this->_page > 1)
                $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$prev" . "$ext'>Назад</a></li>";
            else
                $pagination .= "<li class='page-item disabled'><a class='page-link' href='#'>Назад</a></li>";

            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $this->_page)
                        $pagination .= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                    else
                        $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($this->_page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $this->_page)
                            $pagination .= "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                        else
                            $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
                    }
                    $pagination .= "<li class='page-item disabled'><a class='page-link'>...</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$lpm1" . "$ext'>$lpm1</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$lastpage" . "$ext'>$lastpage</a></li>";
                } elseif ($lastpage - ($adjacents * 2) > $this->_page && $this->_page > ($adjacents * 2)) {
                    $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=1" . "$ext'>1</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=2" . "$ext'>2</a></li>";
                    $pagination .= "<li class='page-item disabled'><a class='page-link'>...</a></li>";
                    for ($counter = $this->_page - $adjacents; $counter <= $this->_page + $adjacents; $counter++) {
                        if ($counter == $this->_page)
                            $pagination .= "<li class='page-item active'><a class='page-link'>$counter</a>";
                        else
                            $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
                    }
                    $pagination .= "<li class='page-item disabled'><a class='page-link'>..</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$lpm1" . "$ext'>$lpm1</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$lastpage" . "$ext'>$lastpage</a></li>";
                } else {
                    $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=1" . "$ext'>1</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=2" . "$ext'>2</a></li>";
                    $pagination .= "<li class='page-item disabled'><a class='page-link'>..</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $this->_page)
                            $pagination .= "<li class='page-item active'><a class='page-link' class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$counter" . "$ext'>$counter</a></li>";
                    }
                }
            }

            if ($this->_page < $counter - 1)
                $pagination .= "<li class='page-item'><a class='page-link' href='" . $path . "$this->_instance=$next" . "$ext'>Вперед</a></li>";
            else
                $pagination .= "<li class='page-item disabled'><a class='page-link'>Вперед</a></li>";
            $pagination .= "</ul>\n";
        }

        return $pagination;
    }
}
