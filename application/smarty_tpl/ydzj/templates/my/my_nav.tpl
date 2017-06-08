    <div class="mynav">
        <ul>
            <li class="segment"><h3>个人中心</h3></li>
            <li {if $uri_string == 'my/index'}class="current"{/if}><i class="fa fa-user"></i><a href="{site_url('my/index')}">基本资料</a></li>
            <li {if $uri_string == 'my_pm/index'}class="current"{/if}><i class="fa fa-envelope-o"></i><a href="{site_url('my_pm/index')}"><span>消息通知</span>{if $unreadCount}<strong>({if $unreadCount >= 100}99+{else}{$unreadCount}{/if}未读)</strong>{/if}</a></li>
            <li {if $uri_string == 'my/change_psw'}class="current"{/if}><i class="fa fa-user-secret"></i><a href="{site_url('my/change_psw')}">修改密码</a></li>
            <li {if $uri_string == 'my_pm/setting'}class="current"{/if}><i class="fa fa-bell"></i><a href="{site_url('my_pm/setting')}">提醒设置</a></li>
            <li {if $uri_string == 'my/seller_verify'}class="current"{/if}><a href="{site_url('my/seller_verify')}">卖家认证</a></li>
            <li class="segment"><h3>数据管理</h3></li>
            <li {if $uri_string == 'hu/index'}class="current"{/if}><a href="{site_url('hu/index')}">户主管理</a></li>
            <li {if $uri_string == 'hu/import'}class="current"{/if}><a href="{site_url('hp/import')}">户主导入</a></li>
            <li {if $uri_string == 'house/index'}class="current"{/if}><a href="{site_url('hp/index')}">宅管理</a></li>
            <li {if $uri_string == 'house/add'}class="current"{/if}><a href="{site_url('house/import')}">建筑物</a></li>
            <li class="segment"><h3>地图功能</h3></li>
            <li {if $uri_string == 'usermap/index'}class="current"{/if}><a href="{site_url('usermap/index')}">浏览地图</a></li>
            <li class="segment"><h3>求货区</h3></li>
            <li {if $uri_string == 'hp/index'}class="current"{/if}><a href="{site_url('hp/index')}">求货查询</a></li>
            <li {if $uri_string == 'hp/add'}class="current"{/if}><a href="{site_url('hp/add')}">发布求货</a></li>
            <li {if $uri_string == 'hp/import'}class="current"{/if}><a href="{site_url('hp/import')}">导入求货</a></li>
            <li {if $uri_string == 'my_req/recent' || $uri_string == 'my_req/history'}class="current"{/if}><a href="{site_url('my_req/recent')}">我的求货</a></li>
            <li class="segment"><h3>库存</h3></li>
            <li {if $uri_string == 'inventory/index'}class="current"{/if}><a href="{site_url('inventory/index')}">我的库存</a></li>
        </ul>
    </div>