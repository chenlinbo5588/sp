    <div class="mynav">
        <ul>
            <li class="segment"><h3>个人中心</h3></li>
            <li><i class="fa fa-user"></i><a href="{site_url('my/index')}">基本资料</a></li>
            <li><i class="fa fa-envelope-o"></i><a href="{site_url('my_pm/index')}"><span>消息通知</span></a></li>
            <li><i class="fa fa-user-secret"></i><a href="{site_url('my/change_psw')}">修改密码</a></li>
            <li><i class="fa fa-bell"></i><a href="{site_url('my_pm/setting')}">提醒设置</a></li>
            <li><a href="{site_url('my/seller_verify')}">卖家认证</a></li>
            <li class="segment"><h3>团队</h3></li>
            <li><a href="{site_url('lab/org/?oid=')}{$currentOrgination['oid']}">{$currentOrgination['name']|escape}</a></li>
            <li><a href="{site_url('lab/index')}">加入的团队</a></li>
            <li class="segment"><h3>货品</h3></li>
            <li><a href="{site_url('lab_goods/index')}">货品查询</a></li>
            <li><a href="{site_url('lab_gcate/index')}">货品分类</a></li>
            <li><a href="{site_url('lab_measure/index')}">度量单位</a></li>
            <li class="segment"><h3>实验员</h3></li>
            <li><a href="{site_url('lab_user/index')}">实验员查询</a></li>
        </ul>
    </div>