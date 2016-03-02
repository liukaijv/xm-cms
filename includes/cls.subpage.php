<?php

class Subpage
{                        //使用时先实例化此类，然后调用分页样式方法subPageCss2()输出分页字符串，在Smarty是最好用
    private $each_disNums;            //每页显示的条目数
    private $nums;                    //总条目数
    private $current_page;            //当前被选中的页
    private $sub_pages;               //每次显示的页数
    private $pageNums;                //总页数
    private $page_array = array();    //用来构造分页的数组
    private $subPage_link;            //每个分页的链接

    //__construct是SubPages的构造函数，用来在创建类的时候自动运行.

    //@$each_disNums  每页显示的条目数

    // @nums    总条目数

    //  @current_num    当前被选中的页

    //@sub_pages      每次显示的页数

    //  @subPage_link   每个分页的链接

    //@subPage_type   显示分页的类型

    //当@subPage_type=2的时候为经典分页样式        example：  当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]

    function __construct($each_disNums, $nums, $current_page, $sub_pages, $subPage_link)
    {
        //intval通过使用特定的进制转换（默认是十进制），返回变量 var 的 integer 数值
        $this->each_disNums = intval($each_disNums);
        $this->nums = intval($nums);
        if (!$current_page) {
            $this->current_page = 1;
        } else {
            $this->current_page = intval($current_page);
        }
        $this->sub_pages = intval($sub_pages);
        $this->pageNums = ceil($this->nums / $this->each_disNums);
        $this->subPage_link = $subPage_link;
    }


    //__destruct析构函数，当类不在使用的时候调用，该函数用来释放资源
    function __destruct()
    {
        unset($each_disNums);
        unset($nums);
        unset($current_page);
        unset($sub_pages);
        unset($pageNums);
        unset($page_array);
        unset($subPage_link);
        unset($subPage_type);
    }

    //用来给建立分页的数组初始化的函数。
    function initArray()
    {
        for ($i = 0; $i < $this->sub_pages; $i++) {
            $this->page_array[$i] = $i;
        }
        return $this->page_array;
    }


    //construct_num_Page该函数使用来构造显示的条目
    // 即使：[1][2][3][4][5][6][7][8][9][10]
    function construct_num_Page()
    {
        if ($this->pageNums < $this->sub_pages) {
            $current_array = array();
            for ($i = 0; $i < $this->pageNums; $i++) {
                $current_array[$i] = $i + 1;
            }
        } else {
            $current_array = $this->initArray();
            if ($this->current_page <= 3) {
                for ($i = 0; $i < count($current_array); $i++) {
                    $current_array[$i] = $i + 1;
                }
            } elseif ($this->current_page <= $this->pageNums && $this->current_page > $this->pageNums - $this->sub_pages + 1) {
                for ($i = 0; $i < count($current_array); $i++) {
                    $current_array[$i] = ($this->pageNums) - ($this->sub_pages) + 1 + $i;
                }
            } else {
                for ($i = 0; $i < count($current_array); $i++) {
                    $current_array[$i] = $this->current_page - 2 + $i;
                }
            }
        }
        return $current_array;
    }

    function get_pages()
    {
        $pages = array();
        $all_page = ceil($this->nums / $this->each_disNums);
        for ($i = 1; $i <= $all_page; $i++) {
            $pages[] = $i;
        }
        return $pages;
    }

    //构造经典模式的分页
    //当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]
    function subpagehtml()
    {
        $subPageCss2Str = "";
        $subPageCss2Str .= "<span>共" . $this->nums . "条&nbsp;当前第 " . $this->current_page . "/" . $this->pageNums . " 页 <span>";
        if ($this->current_page > 1) {
            $firstPageUrl = $this->subPage_link . "1";
            $prewPageUrl = $this->subPage_link . ($this->current_page - 1);
            $subPageCss2Str .= "&nbsp;<a href='" . $firstPageUrl . "' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">首页</a>&nbsp; ";
            $subPageCss2Str .= "&nbsp;<a href='" . $prewPageUrl . "' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">上一页</a>&nbsp; ";
        } else {
            $subPageCss2Str .= "&nbsp;<a href='#' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\" onclick=\"return false;\">首页</a>&nbsp; ";
            $subPageCss2Str .= "&nbsp;<a href='#' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\" onclick=\"return false;\">上一页</a>&nbsp; ";
        }
        $a = $this->construct_num_Page();
        for ($i = 0; $i < count($a); $i++) {
            $s = $a[$i];
            if ($s == $this->current_page) {
                $subPageCss2Str .= "&nbsp;<span style='color:red; font-weight:bold;'>" . $s . "</span>&nbsp;";
            } else {
                $url = $this->subPage_link . $s;
                $subPageCss2Str .= "<a href='" . $url . "' style='font-weight:bold; color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">&nbsp;" . $s . "&nbsp;</a>";
            }
        }

        $all_page = ceil($this->nums / $this->each_disNums);
        if ($this->current_page < $this->pageNums) {
            $lastPageUrl = $this->subPage_link . $this->pageNums;
            $nextPageUrl = $this->subPage_link . ($this->current_page + 1);
            $subPageCss2Str .= " &nbsp;<a href='" . $nextPageUrl . "' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">下一页</a>&nbsp; ";
            $subPageCss2Str .= "&nbsp;<a href='" . $lastPageUrl . "' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">尾页</a>&nbsp; ";
        } else {
            $subPageCss2Str .= "&nbsp;<a href='#' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\" onclick=\"return false;\">下一页</a>&nbsp; ";
            $subPageCss2Str .= "&nbsp;<a href='#' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\" onclick=\"return false;\">尾页</a>&nbsp;";
        }
        return array('subpagehtml' => $subPageCss2Str, 'pages' => $this->get_pages(), 'link' => $this->subPage_link, 'curr_page' => $this->current_page, 'all_page' => $all_page);
    }

    //此方法针对伪静态分页，只要对url重写，传入index-1-2-  重写index-1-2-page.htm
    function subpagehtml_html()
    {
        $subPageCss2Str = "";
        $subPageCss2Str .= "<span>共" . $this->nums . "条&nbsp;当前第 " . $this->current_page . "/" . $this->pageNums . " 页 <span>";
        if ($this->current_page > 1) {
            $firstPageUrl = $this->subPage_link . "1.htm";
            $prewPageUrl = $this->subPage_link . ($this->current_page - 1) . ".htm";
            $subPageCss2Str .= "&nbsp;<a href='" . $firstPageUrl . "' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">首页</a>&nbsp; ";
            $subPageCss2Str .= "&nbsp;<a href='" . $prewPageUrl . "' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">上一页</a>&nbsp; ";
        } else {
            $subPageCss2Str .= "&nbsp;<a href='#' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\" onclick=\"return false;\">首页</a>&nbsp; ";
            $subPageCss2Str .= "&nbsp;<a href='#' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\" onclick=\"return false;\">上一页</a>&nbsp; ";
        }
        $a = $this->construct_num_Page();
        for ($i = 0; $i < count($a); $i++) {
            $s = $a[$i];
            if ($s == $this->current_page) {
                $subPageCss2Str .= "&nbsp;<span style='color:red; font-weight:bold;'>" . $s . "</span>&nbsp;";
            } else {
                $url = $this->subPage_link . $s . ".htm";
                $subPageCss2Str .= "<a href='" . $url . "' style='font-weight:bold; color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">&nbsp;" . $s . "&nbsp;</a>";
            }
        }

        $all_page = ceil($this->nums / $this->each_disNums);
        if ($this->current_page < $this->pageNums) {
            $lastPageUrl = $this->subPage_link . $this->pageNums . ".htm";
            $nextPageUrl = $this->subPage_link . ($this->current_page + 1) . ".htm";
            $subPageCss2Str .= " &nbsp;<a href='" . $nextPageUrl . "' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">下一页</a>&nbsp; ";
            $subPageCss2Str .= "&nbsp;<a href='" . $lastPageUrl . "' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\">尾页</a>&nbsp; ";
        } else {
            $subPageCss2Str .= "&nbsp;<a href='#' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\" onclick=\"return false;\">下一页</a>&nbsp; ";
            $subPageCss2Str .= "&nbsp;<a href='#' style='color:#666;' onmouseover=\"this.style.color='#f00'\" onmouseout=\"this.style.color='#666'\" onclick=\"return false;\">尾页</a>&nbsp;";
        }
        return array('subpagehtml' => $subPageCss2Str, 'pages' => $this->get_pages(), 'link' => $this->subPage_link, 'curr_page' => $this->current_page, 'all_page' => $all_page);
    }

    function subpagehtml_a()
    {
        $subPageCss2Str = "";
        if ($this->current_page > 1) {
            $firstPageUrl = $this->subPage_link . "1";
            $prewPageUrl = $this->subPage_link . ($this->current_page - 1);
            $subPageCss2Str .= "<a href='" . $firstPageUrl . "'>首页</a>";
            $subPageCss2Str .= "<a href='" . $prewPageUrl . "' >上一页</a>";
        } else {
            $subPageCss2Str .= "<a href='javascript:' class=\"disable\">首页</a>";
            $subPageCss2Str .= "<a href='javascript:' class=\"disable\">上一页</a>";
        }
        $a = $this->construct_num_Page();
        for ($i = 0; $i < count($a); $i++) {
            $s = $a[$i];
            if ($s == $this->current_page) {
                $subPageCss2Str .= "<a href='javascript:' class=\"active\">" . $s . "</a>";
            } else {
                $url = $this->subPage_link . $s;
                $subPageCss2Str .= "<a href='" . $url . "' >" . $s . "</a>";
            }
        }

        $all_page = ceil($this->nums / $this->each_disNums);
        if ($this->current_page < $this->pageNums) {
            $lastPageUrl = $this->subPage_link . $this->pageNums;
            $nextPageUrl = $this->subPage_link . ($this->current_page + 1);
            $subPageCss2Str .= "<a href='" . $nextPageUrl . "'>下一页</a>";
            $subPageCss2Str .= "<a href='" . $lastPageUrl . "'>尾页</a>";
        } else {
            $subPageCss2Str .= "<a href='javascript:' class=\"disable\">下一页</a>";
            $subPageCss2Str .= "<a href='javascript:' class=\"disable\">尾页</a>";
        }
        return array('subpagehtml' => $subPageCss2Str, 'pages' => $this->get_pages(), 'link' => $this->subPage_link, 'curr_page' => $this->current_page, 'all_page' => $all_page);
    }
}