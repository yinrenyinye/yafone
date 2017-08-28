<?php

/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/11
 * Time: 18:00
 */
class Common_Pagination
{
    /** @var string the pagination base url */
    protected $_base_url;

    /** @var int the pagination total page */
    protected $_pagetotal;

    /** @var int the pagination current page */
    protected $_cur_page;

    /** @var int the pagination data numbers */
    protected $_pagesize;

    /** @var array the pagination request filter */
    protected $_query_str = [];

    /** @var int the pagination show link numbers */
    protected $_show_link_nums = 5;

    /** @var string the pagination page link */
    protected $_page_link = '';

    public function __construct()
    {

    }

    /**
     * 初始化配置
     */
    public function config()
    {
        if (func_num_args() > 1) {
            $arguments = func_get_args();
            for ($i = 0; $i < func_num_args(); $i++) {
                $set_action = 'set_' . key($arguments[$i]);
                $this->$set_action(current($arguments[$i]));
            }
        } else {
            if (is_array(func_get_arg(0))) {
                foreach (func_get_arg(0) as $k => $item) {
                    $set_action = 'set_' . $k;
                    $this->$set_action($item);
                }
            }
        }
    }

    /*
     * 创建分页相关信息
     * @param string 确定返回数据形式 value (html | array)
     * @return mixed
     */
    public function create_links($type = 'html')
    {
        if (!in_array($type, array('html', 'array'))) {
            return array('type' => 'error', 'msg' => 'Invalid Params!');
        }

        if (isset($this->_pagesize) && !empty($this->_pagesize)) {
            $query_all = array_merge($this->_query_str, ['pagesize' => $this->_pagesize], ['page' => '']);
        } else {
            $query_all = array_merge($this->_query_str, ['page' => '']);
        }

        if ('html' == $type) {
            $query_link = "?" . http_build_query($query_all);
        } else {
            $query_link = http_build_query($query_all);
        }

        if ('array' === $type) {
            $link_arr = ['base_url' => $this->_base_url, 'cur_page' => $this->_cur_page, 'loop_page' => []];
        }

        if (1 < $this->_cur_page) {
            if ('html' === $type) {
                $this->_page_link .= '<a href="' . $this->_base_url . $query_link . '1">首页</a>&nbsp;&nbsp;<a href="' . $this->_base_url . $query_link . ($this->_cur_page - 1) . '">上一页</a>';
            }

            if ('array' === $type) {
                $link_arr['first_page'] = $query_link . '1';
                $link_arr['pre_page'] = $query_link . ($this->_cur_page - 1);
            }
        }

        if ($this->_pagetotal <= $this->_show_link_nums) {

            for ($i = 1; $i <= $this->_pagetotal; $i++) {
                if ('html' === $type) {
                    if ($i == $this->_cur_page) {
                        $this->_page_link .= '&nbsp;&nbsp;<span>' . $i . '</span>&nbsp;&nbsp;';
                    } else {
                        $this->_page_link .= '&nbsp;&nbsp;<a href="' . $this->_base_url . $query_link . $i . '">' . $i . '</a>&nbsp;&nbsp;';
                    }
                }

                if ('array' === $type) {
                    $link_arr['loop_page'][$i] = $query_link . $i;
                }
            }

        } else {

            $step = floor($this->_show_link_nums / 2);

            if ($this->_cur_page <= $this->_show_link_nums - $step) {

                for ($i = 1; $i <= $this->_show_link_nums; $i++) {
                    if ('html' === $type) {
                        if ($i == $this->_cur_page) {
                            $this->_page_link .= '&nbsp;&nbsp;<span>' . $i . '</span>&nbsp;&nbsp;';
                        } else {
                            $this->_page_link .= '&nbsp;&nbsp;<a href="' . $this->_base_url . $query_link . $i . '">' . $i . '</a>&nbsp;&nbsp;';
                        }
                    }

                    if ('array' === $type) {
                        $link_arr['loop_page'][$i] = $query_link . $i;
                    }
                }

            } else {

                if ($this->_cur_page + $step > $this->_pagetotal) {

                    for ($i = $this->_pagetotal - $this->_show_link_nums + 1; $i <= $this->_pagetotal; $i++) {
                        if ('html' === $type) {
                            if ($i == $this->_cur_page) {
                                $this->_page_link .= '&nbsp;&nbsp;<span>' . $i . '</span>&nbsp;&nbsp;';
                            } else {
                                $this->_page_link .= '&nbsp;&nbsp;<a href="' . $this->_base_url . $query_link . $i . '">' . $i . '</a>&nbsp;&nbsp;';
                            }
                        }

                        if ('array' === $type) {
                            $link_arr['loop_page'][$i] = $query_link . $i;
                        }
                    }

                } else {
                    for ($i = $this->_cur_page - $step; $i <= $this->_cur_page + $step; $i++) {
                        if ('html' === $type) {
                            if ($i == $this->_cur_page) {
                                $this->_page_link .= '&nbsp;&nbsp;<span>' . $i . '</span>&nbsp;&nbsp;';
                            } else {
                                $this->_page_link .= '&nbsp;&nbsp;<a href="' . $this->_base_url . $query_link . $i . '">' . $i . '</a>&nbsp;&nbsp;';
                            }
                        }

                        if ('array' === $type) {
                            $link_arr['loop_page'][$i] = $query_link . $i;
                        }
                    }
                }

            }
        }

        if ($this->_cur_page < $this->_pagetotal) {
            if ('html' === $type) {
                $this->_page_link .= '<a href="' . $this->_base_url . $query_link . ($this->_cur_page + 1) . '">下一页</a>&nbsp;&nbsp;<a href="' . $this->_base_url . $query_link . $this->_pagetotal . '">尾页</a>';
            }

            if ('array' === $type) {
                $link_arr['next_page'] = $query_link . ($this->_cur_page + 1);
                $link_arr['last_page'] = $query_link . $this->_pagetotal;
            }
        }

        if ('html' === $type) {
            return $this->_page_link;
        }

        if ('array' === $type) {
            return $link_arr;
        }

        return false;
    }

    public function set_base_url($base_url)
    {
        return $this->_base_url = $base_url;
    }

    public function set_pagetotal($pagetotal)
    {
        return $this->_pagetotal = $pagetotal;
    }

    public function set_cur_page($cur_page)
    {
        return $this->_cur_page = $cur_page;
    }

    public function set_pagesize($pagesize)
    {
        return $this->_pagesize = $pagesize;
    }

    public function set_query_str($query_str)
    {
        return $this->_query_str = $query_str;
    }

    public function set_show_link_nums($show_link_nums)
    {
        return $this->_show_link_nums = $show_link_nums;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
        return true;
    }

}