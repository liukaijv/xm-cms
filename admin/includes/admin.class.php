<?php

class Web
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

    /*后台登陆
    *	$username  	//用户名
    *	$userpwd	//用户密码
    */
    function do_login($username, $userpwd)
    {
        if (trim($username) && trim($userpwd)) {
            $conditions = " admin_username='" . $username . "' and admin_userpwd='" . md5($userpwd) . "'";
            if ($admin_info = $this->get($conditions, "admin")) {
                return $admin_info;
            } else {
                return [];
            }
        }
    }

    /*系统日志
    * action  //动作
    */
    function admin_log($action)
    {
        if (!empty($action)) {
            $data = array(
                'admin_id' => $_SESSION['admin_info']['admin_id'],
                'admin_username' => $_SESSION['admin_info']['admin_username'],
                'login_ip' => get_real_ip(),
                'action' => $action,
                'add_time' => time()
            );
            if ($iid = $this->db->insert($data, "admin_log")) {
                return true;
            }
        }
        return false;
    }

    /*获取后管理栏目
    ** parent_id  //父ID
    * is_show    //是否显示
    * order      //排序
    */
    function get_back_menu($parent_id = 0, $is_show = 0, $order = 'sort_order asc,menu_id asc')
    {
        $where = '';
        if ($is_show == 1) {
            $where = " and is_show=1";
        } else {
            $where = "";
        }
        $condition = "and parent_id=$parent_id" . $where;

        $sql = "select m.*,s.type_name from " . $this->prefix . "menus as m " .
            "left join " . $this->prefix . "menus_type as s on(m.type_id=s.type_id) " .
            "where 1=1 $condition order by $order";
        $menus_one = $this->db->getAll($sql);

        $menus = '';
        if (count($menus_one) > 0 && is_array($menus_one)) {
            foreach ($menus_one as $k2 => $menu) {
                $menus[$k2]['menu_id'] = $menu['menu_id'];
                $menus[$k2]['link_id'] = $menu['link_id'];
                $menus[$k2]['menu_name'] = $menu['menu_name'];
                $menus[$k2]['menu_name_en'] = $menu['menu_name_en'];
                $menus[$k2]['menu_url'] = $menu['menu_url'];
                $menus[$k2]['type_name'] = $menu['type_name'];
                $menus[$k2]['sort_order'] = $menu['sort_order'];
                $menus[$k2]['is_show'] = $menu['is_show'];
                $menus[$k2]['can_delete'] = $menu['can_delete'];
                $menus[$k2]['sub_menus'] = $this->get_back_menu($menu['menu_id'], $is_show);
            }

        }
        return $menus;
    }

    /*获取子栏目
    ** parent_id  //父ID
    * is_show    //是否显示
    * order      //排序
    */
    function get_sub_menu($parent_id = 0, $is_show = 1, $order = 'sort_order asc,menu_id asc')
    {
        $where = '';
        if ($is_show == 1) {
            $where = " and is_show=1";
        } else {
            $where = "";
        }
        $condition = "and parent_id=$parent_id" . $where;
        //$menus2=$this->db->fetchAll("menus",$condition,$order);
        $sql = "select m.*,s.type_name from " . $this->prefix . "menus as m " .
            "left join " . $this->prefix . "menus_type as s on(m.type_id=s.type_id) " .
            "where 1=1 $condition order by $order";
        $menus2 = $this->db->getAll($sql);
        if (count($menus2) > 0) {
            return $menus2;
        } else {
            return [];
        }
    }

    /*
    * 获取所有栏目类型
    */
    function get_menu_type()
    {
        $types = $this->db->fetchAll("menus_type", "disabled = 0", "type_id asc");
        if (is_array($types) > 0) {
            return $types;
        } else {
            return array();
        }
    }

    /*
    * 获取同类型栏目
    */
    function get_same_menu($type_id)
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
    public function get($conditions, $tab)
    {
        if ($conditions && $tab) {
            return $this->db->get_info($conditions, $tab);
        }
    }


    /*是否已缓存*/
    public function is_Htmlcaches($page)
    {
        $str = '';
        foreach ($_GET as $k => $v) {
            $str .= $k . '_' . $v . '_';
        }
        $str = md5('cache_' . $str . $page) . '.html';
        $cache_dir = ROOT . '/temp/htmlcaches/' . $str;
        $this->htmlcaches = $cache_dir;
        if (file_exists($cache_dir)) {
            if (filemtime($cache_dir) + 0 >= time()) {
                echo file_get_contents($cache_dir);
                exit;
            }
        }
    }

    /*创建缓存*/
    public function creat_Htmlcaches($content)
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
                showMsg("请不要使用禁用词汇！");
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

?>