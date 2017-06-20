    <div class="mynav">
        <ul>
            <li class="segment first"><h3>个人中心</h3></li>
            <li {if $uri_string == 'my/index'}class="current"{/if}><a href="{site_url('my/index')}">基本资料</a></li>
            <li {if $uri_string == 'my_pm/index'}class="current"{/if}><a href="{site_url('my_pm/index')}"><span>消息通知</span>{if $unreadCount}<strong>({if $unreadCount >= 100}99+{else}{$unreadCount}{/if}未读)</strong>{/if}</a></li>
            <li {if $uri_string == 'my/change_psw'}class="current"{/if}><a href="{site_url('my/change_psw')}">修改密码</a></li>
            <li {if $uri_string == 'my_pm/setting'}class="current"{/if}><a href="{site_url('my_pm/setting')}">提醒设置</a></li>
            <li class="segment"><h3>数据管理</h3></li>
            <li {if $uri_string == 'person/index'}class="current"{/if}><a href="{site_url('person/index')}">权力人管理</a></li>
            <li {if $uri_string == 'house/index'}class="current"{/if}><a href="{site_url('house/index')}">房屋建筑管理</a></li>
            <li {if $uri_string == 'house/map'}class="current"{/if}><a href="{site_url('house/map')}">浏览地图</a></li>
        </ul>
    </div>