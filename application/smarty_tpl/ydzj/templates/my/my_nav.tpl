    <div class="mynav">
        <ul>
            <li class="segment"><h3>个人中心</h3></li>
            <li {if $uri_string == 'my/index'}class="current"{/if}><i class="fa fa-user"></i><a href="{site_url('my/index')}">基本资料</a></li>
            <li {if $uri_string == 'my_pm/index'}class="current"{/if}><i class="fa fa-envelope-o"></i><a href="{site_url('my_pm/index')}"><span>消息通知</span>{if $unreadCount}<strong>({if $unreadCount >= 100}99+{else}{$unreadCount}{/if}未读)</strong>{/if}</a></li>
            <li {if $uri_string == 'my/change_psw'}class="current"{/if}><i class="fa fa-user-secret"></i><a href="{site_url('my/change_psw')}">修改密码</a></li>
            <li {if $uri_string == 'my_pm/setting'}class="current"{/if}><i class="fa fa-bell"></i><a href="{site_url('my_pm/setting')}">提醒设置</a></li>
            <li {if $uri_string == 'my/seller_verify'}class="current"{/if}><a href="{site_url('my/seller_verify')}">卖家认证</a></li>
            <li class="segment"><h3>求货区</h3></li>
            <li {if $uri_string == 'hp/index'}class="current"{/if}><a href="{site_url('hp/index')}">求货查询</a></li>
            <li {if $uri_string == 'hp/add'}class="current"{/if}><a href="{site_url('hp/add')}">发布求货</a></li>
            <li {if $uri_string == 'hp/import'}class="current"{/if}><a href="{site_url('hp/import')}">导入求货</a></li>
            <li {if $uri_string == 'my_req/recent' || $uri_string == 'my_req/history'}class="current"{/if}><a href="{site_url('my_req/recent')}">我的求货</a></li>
            <li class="segment"><h3>库存</h3></li>
            <li {if $uri_string == 'inventory/index'}class="current"{/if}><a href="{site_url('inventory/index')}">我的库存</a></li>
        </ul>
    </div>