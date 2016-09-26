    <div class="mynav">
        <ul>
            <li class="segment"><h3>个人中心</h3></li>
            <li><i class="fa fa-user"></i><a href="{site_url('my/index')}">基本资料</a></li>
            <li><i class="fa fa-envelope-o"></i><a href="{site_url('my/index')}"><span>消息通知</span></a><em class="navtip">({$profile['basic']['newpm']})</em></li>
            <li><i class="fa fa-user-secret"></i><a href="{site_url('my/change_psw')}">修改密码</a></li>
            <li><i class="fa fa-bell"></i><a href="{site_url('my/tip')}">提醒设置</a></li>
            <li><a href="{site_url('my/seller_verify')}">卖家认证</a></li>
            <li class="segment"><h3>求货区</h3></li>
            <li><a href="{site_url('goods/index')}">求货查询</a></li>
            <li><a href="{site_url('my_req/index')}">我的求货</a></li>
            <li><a href="{site_url('my_inventory/index')}">我的库存</a></li>
        </ul>
    </div>