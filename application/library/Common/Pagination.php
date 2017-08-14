<?php
/**
 * Created by PhpStorm.
 * User: micle
 * Date: 2017/8/11
 * Time: 18:00
 */

namespace Common;


class Pagination
{
    /** @var string the pagination base url */
    protected $_base_url;

    /** @var int the pagination total page */
    protected $_pagetotal;

    /** @var int the pagination current page */
    protected $_cur_page;

    /** @var int the pagination data numbers*/
    protected $_pagesize;

    /** @var array the pagination request filter  */
    protected $_query_str;

    /** @var int the pagination show link numbers */
    protected $_show_link_nums;

    public function __construct()
    {

    }

    public function config()
    {
        if(func_num_args() > 1){
            $arguments = func_get_args();

            for($i = 0;$i < func_num_args();$i++){
                $set_action = 'set_'.key($arguments[$i]);
                $this->$set_action(current($arguments[$i]));
            }

        }else{
            if(is_array(func_get_arg(0))){

                foreach(func_get_arg(0) as $k => $item){
                    $set_action = 'set_'.$k;
                    $this->$set_action($item);
                }

            }else{
                return false;
            }
        }
    }

    public function create_links()
    {

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

    public function __set($name , $value)
    {
        return $this->$name = $value;
    }

}