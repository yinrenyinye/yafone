<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class CommentController extends Yaf_Controller_Abstract {

	/** 
     * 默认动作
     * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
     * 对于如下的例子, 当访问http://yourhost/yafone/index/index/index/name/root 的时候, 你就会发现不同
     */
	public function indexAction($name = "Stranger") {

        if (!empty($this->getRequest()->getQuery("page"))) {
            $page = intval($this->getRequest()->getQuery("page"));
        } else {
            $page = 1;
        }
        if ($page <= 0) $page = 1;

	    $comment = new CommentModel();

	    $total = $comment->count();

        $pagesize = 5;

        $pagetotal = ceil($total['total'] / $pagesize);

        if($page > $pagetotal) $page = $pagetotal;

        $offset = ($page - 1) * $pagesize;

        $data = $comment->get_list($pagesize,$offset);

	    $pagination = new Common_Pagination();

        $pagination->config([
            'base_url' => 'http://yaf.brightdh.com/comment/index',
            'pagetotal' => $pagetotal,
            'cur_page' => $page
        ]);

        $page_link = $pagination->create_links('array');
        var_dump($data,$page_link);
        return false;
	}
}
