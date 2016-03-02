<?php

class Front
{
    private $db;                //数据库链接对象
    private $prefix;            //数据库表前缀
    public static $config;        //站点配置
    private $htmlcaches;        //站点配置

    /*构造函数*/
    function __construct($db, $prefix)
    {
        $this->db = $db;
        $this->prefix = $prefix;
    }

    /*前台登陆
    *	$username  	//用户名
    *	$userpwd	//用户密码
    */
    public function do_login($username, $userpwd)
    {
        if (trim($username) && trim($userpwd)) {
            $conditions = " user_name='" . $username . "' and user_pwd='" . md5($userpwd) . "'";
            if ($user_info = $this->get($conditions, "user")) {
                return $user_info;
            } else {
                return [];
            }
        }
    }

    /*系统日志
    * action  //动作
    */
    public function user_log($action)
    {
        if (!empty($action)) {
            $data = array(
                'user_id' => $_SESSION['user_info']['user_id'],
                'user_name' => $_SESSION['user_info']['user_name'],
                'login_ip' => get_real_ip(),
                'action' => $action,
                'add_time' => time()
            );
            if ($iid = $this->db->insert($data, "user_log")) {
                return true;
            }
        }
        return false;
    }

    /*获取前台导航
    ** parent_id  //父ID
    * is_show    //是否显示
    * order      //排序
    */
    public function get_menus($parent_id = 0, $num = 0, $is_show = 1, $order = 'sort_order asc,menu_id asc')
    {
        $where = $limit = "";
        if ($is_show == 1) {
            $where = " and is_show=1";
        }
        if (!$parent_id) {
            $parent_id = 0;
        }
        if ($num > 0) {
            $limit = "limit $num";
        }
        $condition = "and parent_id=$parent_id" . $where;
        $sql = "select m.*,s.type_name from " . $this->prefix . "menus as m " .
            "left join " . $this->prefix . "menus_type as s on(m.type_id=s.type_id) " .
            "where 1=1 $condition order by $order $limit";

        $menus = $this->db->getAll($sql);
        if (count($menus) > 0 && is_array($menus)) {
            foreach ($menus as $k2 => $menu) {
                $menus[$k2]['menu_id'] = $menu['menu_id'];
                $menus[$k2]['link_id'] = $menu['link_id'];
                $menus[$k2]['menu_name'] = $menu['menu_name'];
                $menus[$k2]['menu_name_en'] = $menu['menu_name_en'];
                $menus[$k2]['menu_url'] = $menu['menu_url'];
                $menus[$k2]['type_name'] = $menu['type_name'];
                $menus[$k2]['sort_order'] = $menu['sort_order'];
                $menus[$k2]['is_show'] = $menu['is_show'];
                $sub_menu = $this->db->getOne("select menu_id from " . $this->prefix . "menus where parent_id=" . $menu['menu_id'] . " order by $order");
                $menus[$k2]['sub_id'] = $sub_menu['menu_id'];
                $menus[$k2]['sub_menus'] = $this->get_sub_menus($menu['menu_id'], $is_show);

            }

        }
        return $menus;
    }

    /*获取子栏目
    ** parent_id  //父ID
    * is_show    //是否显示
    * order      //排序
    */
    public function get_sub_menus($parent_id = 0, $is_show = 1, $order = 'sort_order asc,menu_id asc')
    {
        if ($is_show == 1) {
            $where = " and is_show=1";
        } else {
            $where = "";
        }
        $condition = "and parent_id=$parent_id" . $where;
        $sql = "select m.*,s.type_name from " . $this->prefix . "menus as m " .
            "left join " . $this->prefix . "menus_type as s on(m.type_id=s.type_id) " .
            "where 1=1 $condition order by $order";
        $menus = $this->db->getAll($sql);
        if (count($menus) > 0) {
            return $menus;
        } else {
            return [];
        }
    }

    /*
    ** 递归获取最顶级栏目ID
    */
    public function get_top_mid($mid)
    {
        if ($mid) {
            $menu = $this->get("menu_id=$mid", "menus");
            if ($menu['parent_id'] < 1) {
                return $mid;
            } else {
                return $this->get_top_mid($menu['parent_id']);
            }
        }
    }

    // 获取顶级菜单
    public function get_top_menu($mid)
    {
        if ($mid) {
            $menu = $this->get("menu_id=$mid", "menus");
            if ($menu['parent_id'] < 1) {
                return $menu;
            } else {
                return $this->get_top_menu($menu['parent_id']);
            }
        }
    }

    /*
    ** 获取上一级栏目
    */
    public function get_up_menu($mid)
    {
        if ($mid) {
            $menu = $this->get("menu_id=$mid", "menus");
            $parent = $this->get("menu_id='" . $menu['parent_id'] . "'", "menus");
            return $parent;
        }
    }

    /*
    ** 取当前位置
    */
    public function get_position($mid)
    {
        if ($mid) {
            $menu = $this->get("menu_id=$mid", "menus");
            if ($menu['parent_id'] < 1) {
                $sub_menu = $this->db->getOne("select menu_id from " . $this->prefix . "menus where parent_id=" . $menu['menu_id'] . " order by sort_order asc");
                $menu['sub_id'] = $sub_menu['menu_id'];
                return [$menu];
            } else {
                $top_menu = $this->get("menu_id=" . $this->get_top_mid($mid), "menus");
                $sub_menu = $this->db->getOne("select menu_id from " . $this->prefix . "menus where parent_id=" . $top_menu['menu_id'] . " order by sort_order asc");
                $parent_menu = $this->get_up_menu($mid);
                $top_menu['sub_id'] = $sub_menu['menu_id'];
                if ($parent_menu['parent_id'] < 1) {
                    return [$top_menu, $menu];
                } else {
                    return [$top_menu, $parent_menu, $menu];
                }
            }
        }
    }

    /*
    ** 取标题
    */
    public function get_title($mid)
    {
        if ($mid) {
            $menu = $this->get("menu_id=$mid", "menus");
            if ($menu['parent_id'] < 1) {
                return [$menu, ['menu_name' => $GLOBALS['CFG']['site_title']]];
            } else {
                $top_menu = $this->get("menu_id=" . $this->get_top_mid($mid), "menus");
                return [$menu, $top_menu, ['menu_name' => $GLOBALS['CFG']['site_title']]];
            }
        }
    }

    /*
    * 根据栏目类型获取操作表
    */
    public function get_tab($mid)
    {
        $table = '';
        if ($mid) {
            $menu = $this->get("menu_id=$mid", "menus");
            if ($menu['type_id']) {
                switch (intval($menu['type_id'])) {
                    case '1':
                        $table = 'simple';
                        break;
                    case '2':
                        $table = 'article';
                        break;
                    case '3':
                        $table = 'pro';
                        break;
                    case '4':
                        $table = 'case';
                        break;
                    case '5':
                        $table = 'feedback';
                        break;
                    default:
                        $table = '';
                        break;
                }
            }
        }
        return $table;
    }

    /*
    * 获取左侧菜单标题
    */
    public function get_left_title($top_id)
    {
        $title_zh = $title_en = '';
        $menu = $this->get("menu_id=$top_id", "menus");
        if (count($menu)) {
            $title_zh = $menu['menu_name'];
            $title_en = $menu['menu_name_en'];
        }
        return array('title_zh' => $title_zh, 'title_en' => $title_en);
    }

    /*
    * 获取所有栏目类型
    */
    public function get_menu_type()
    {
        $types = $this->db->fetchAll("menus_type", 1, "type_id asc");
        if (is_array($types) > 0) {
            return $types;
        } else {
            return [];
        }
    }

    /*
    * 获取同类型栏目
    */
    public function get_same_menu($type_id)
    {
        if ($type_id) {
            $same_menus = $this->db->fetchAll("menus", "type_id=$type_id", "menu_id asc");
        }
        if (is_array($same_menus)) {
            return $same_menus;
        } else {
            return array();
        }
    }

    /*获取指定表和条件的单条记录
    * $condition   	//条件
    * $tab			//表名
    */
    public function get($conditions, $table)
    {
        if ($conditions && $table) {
            return $this->db->get_info($conditions, $table);
        }
    }

    /*
    **	获取文章
    */
    public function get_articles($mid, $num = 0, $is_top = 0, $is_rec = 0, $order = "add_time desc")
    {
        $where = $limit = '';
        if (is_array($mid)) {
            $where .= " and a.menu_id " . db_create_in($mid) . "";
        } elseif ($mid < 0) {
            $where .= " and a.menu_id !=" . abs($mid) . "";
        } elseif ($mid) {
            $where .= " and a.menu_id=$mid";
        }
        if ($is_rec) {
            $where .= " and a.is_recom=1";
        }
        if ($is_top) {
            $where .= " and a.is_top=1";
        }
        if (intval($num)) {
            $limit = " limit $num";
        }
        $sql = "select a.*,m.menu_name from " . $this->prefix . "article as a left join " . $this->prefix . "menus as m on a.menu_id = m.menu_id  where 1=1 $where order by $order $limit";
//        exit($sql);
        $arts = $this->db->getAll($sql);
        return $arts;
    }

    /*
    **	获取视频
    */
    public function get_videos($mid, $num = 0, $is_top = 0, $is_rec = 0, $order = "add_time desc")
    {
        $where = $limit = '';
        if (is_array($mid)) {
            $where .= " and menu_id " . db_create_in($mid) . "";
        } elseif ($mid < 0) {
            $where .= " and menu_id !=" . abs($mid) . "";
        } elseif ($mid) {
            $where .= " and menu_id=$mid";
        }
        if ($is_rec) {
            $where .= " and is_recom=1";
        }
        if ($is_top) {
            $where .= " and is_top=1";
        }
        if (intval($num)) {
            $limit = " limit $num";
        }
        $sql = "select * from " . $this->prefix . "vedio where 1=1 $where order by $order $limit";
        //print_r($sql);exit;
        $arts = $this->db->getAll($sql);
        return $arts;
    }


    /*
    **	获取案例
    */
    public function get_cases($mid, $num = 0, $is_top = 0, $is_rec, $order = "add_time desc")
    {
        $where = $limit = '';
        if (is_array($mid)) {
            $where .= " and menu_id " . db_create_in($mid) . "";
        } else {
            $where .= " and menu_id=$mid";
        }
        if ($is_top) {
            $where .= " and is_top=1";
        }
        if ($is_rec) {
            $where .= " and is_recom=1";
        }
        if (intval($num)) {
            $limit = " limit $num";
        }
        $sql = "select * from " . $this->prefix . "case where 1=1 $where order by $order $limit";
        $cases = $this->db->getAll($sql);
        return $cases;
    }

    /*
    **	获取产品
    */
    public function get_pros($mid, $num = 0, $is_top = 0, $is_rec = 0, $order = "add_time desc")
    {
        $where = $limit = '';
        if (is_array($mid)) {
            $where .= " and menu_id " . db_create_in($mid) . "";
        } else {
            $where .= " and menu_id=$mid";
        }
        if ($is_top) {
            $where .= " and is_top=1";
        }
        if ($is_rec) {
            $where .= " and is_recom=1";
        }
        if (intval($num)) {
            $limit = " limit $num";
        }
        $sql = "select * from " . $this->prefix . "pro where 1=1 $where order by $order $limit";
        $pros = $this->db->getAll($sql);
        return $pros;
    }

    /*
    **	获取评论
    */
    public function get_comments($is_show = 0, $num = 0, $order = "add_time desc")
    {
        $where = $limit = '';
        if ($is_show) {
            $where .= " and is_show=1";
        }
        if (intval($num)) {
            $limit = " limit $num";
        }
        $sql = "select * from " . $this->prefix . "comment where 1=1 $where order by $order $limit";
        $comments = $this->db->getAll($sql);
        return $comments;
    }

    /*
    **	获取链接
    */
    public function get_links($cate_id = 1, $num = 0, $order = "sort_order asc,add_time desc")
    {
        $where = $limit = '';
        if ($cate_id = intval($cate_id)) {
            $where .= " and cate_id=$cate_id";
        }
        if (intval($num)) {
            $limit = " limit $num";
        }
        $sql = "select * from " . $this->prefix . "link where 1=1 $where order by $order $limit";
        $links = $this->db->getAll($sql);
        return $links;
    }

    /*
    **	获取单页图文
    */
    public function get_simple($mid)
    {
        if (!$mid) {
            return;
        }
        $info = $this->get("menu_id=$mid", "simple");
        return $info;
    }

    /*
    **	获取广告
    */
    public function get_ads($pos_id, $num = 5)
    {
        if (!$pos_id) {
            return;
        }
        $sql = "select a.*,p.is_blank from " . $this->prefix . "ad as a left join " . $this->prefix . "ad_pos as p on(a.pos_id=p.pos_id) where a.pos_id=$pos_id order by a.add_time desc limit $num";
        //echo $sql;exit;
        $ads = $this->db->getAll($sql);
        return $ads;
    }

    /*
    **	获取留言
    */
    public function get_messages($num, $is_check = 1)
    {
        $limit = $where = '';
        if ($num > 0) {
            $limit .= " limit $num";
        }
        if ($is_check) {
            $where .= " and is_check=1";
        }
        $sql = "select * from " . $this->prefix . "feedback where 1=1 $where order by add_time desc $limit";
        $feeds = $this->db->getAll($sql);
        if (count($feeds) > 0) {
            return $feeds;
        } else {
            return [];
        }
    }

    /*是否已缓存*/
    public function is_html_cached($page)
    {
        $str = '';
        foreach ($_GET as $k => $v) {
            $str .= $k . '_' . $v . '_';
        }
        $str = md5('cache_' . $str . $page) . '.html';
        $cache_dir = ROOT . '/temp/caches/' . $str;
        $this->htmlcaches = $cache_dir;
        if (file_exists($cache_dir)) {
            if (filemtime($cache_dir) + 0 >= time()) {
                echo file_get_contents($cache_dir);
                exit;
            }
        }
    }

    /*创建缓存*/
    public function create_html_caches($content)
    {
        $fp = fopen($this->htmlcaches, 'w');
        @fwrite($fp, $content);
        fclose($fp);
    }

    /*过滤关键字*/
    public function filter_Key($str)
    {
        if (!empty($this->config['filter_key'])) {
            $check = eregi($this->config['filter_key'], $str);
            if ($check) {
                showMsg("请不要使用禁用词汇！", 1);
            } else {
                return $str;
            }
        } else {
            return $str;
        }
    }

    /*防止sql注入*/
    public function filter_Sql($sql_str)
    {
        $check = eregi('select| insert| and| update| delete|\'|\/\*|\*|\.\.\/|\.\/| union| into| load_file| outfile| or', $sql_str);
        if ($check) {
            showMsg("请不要尝试攻击！", 1);
        } else {
            return $sql_str;
        }
    }
}
