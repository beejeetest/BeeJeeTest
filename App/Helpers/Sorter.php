<?php

namespace App\Helpers;


class Sorter
{
    private $_instance;
    private $_attributes;
    private $_sort;

    /**
     * Sorter constructor.
     * @param $instance
     * @param array $attributes
     * [
     *      'id',
     *      'username',
     *      'email'
     * ]
     * @param array $default
     *
     * 'username asc'
     */
    public function __construct($instance, array $attributes, string $default)
    {
        $this->_instance = $instance;
        $this->_attributes = $attributes;
        $defaultOrder = array_fill_keys($attributes, 'asc');
        array_walk($defaultOrder, function (&$sort, $key) {
            $sort = "$key $sort";
        });
        $this->_sort = ['order' => $default];
    }

    public function getSort()
    {
        if ($sort = isset($_GET[$this->_instance]) ? $_GET[$this->_instance] : null) {
            array_walk($sort, function (&$sort, $key) {
                $sort = "$key $sort";
            });
            $this->_sort['order'] = implode(', ', $sort);
        }
        return $this->_sort;
    }

    public function getCurrentSort()
    {
        return isset($_GET[$this->_instance]) ? "&" . http_build_query([$this->_instance => $_GET[$this->_instance]]) : null;
    }


    public function getSorterLink($attribute)
    {
        $get = $_GET;
        if (isset($get[$this->_instance][$attribute])) {
            $sort = $get[$this->_instance][$attribute];
            $get[$this->_instance][$attribute] = $sort === 'desc' ? 'asc' : 'desc';
        } else {
            $get = array_merge($get, [
                $this->_instance => [
                    $attribute => 'asc'
                ]
            ]);
        }

        // &#8645;
        $link = http_build_query($get);
        $symbol = $get[$this->_instance][$attribute] === 'desc' ? '&#8595;' : '&#8593;';
        return in_array($attribute, $this->_attributes) ? "<a href='/?$link'>{$symbol}</a>" : '';
    }
}
