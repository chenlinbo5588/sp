    <div class="mynav">
        <ul>
            <li class="segment first"><h3>个人中心</h3></li>
            <li {if $uri_string == 'my/base'}class="current"{/if}><a href="{site_url('my/base')}">基本资料</a></li>
            <li {if $uri_string == 'my/change_psw'}class="current"{/if}><a href="{site_url('my/change_psw')}">修改密码</a></li>
            <li {if $uri_string == 'my_pm/index'}class="current"{/if}><a href="{site_url('my_pm/index')}"><span>消息通知</span>{if $unreadCount}<strong>({if $unreadCount >= 100}99+{else}{$unreadCount}{/if}未读)</strong>{/if}</a></li>
            {*<li {if $uri_string == 'my_pm/setting'}class="current"{/if}><a href="{site_url('my_pm/setting')}">提醒设置</a></li>
            <li {if $uri_string == 'my_task/index'}class="current"{/if}><a href="{site_url('my_task/index')}">待办事宜</a></li>*}
            <li class="segment"><h3>数据管理</h3></li>
            {if isset($permission['budongchan/index'])}<li {if $uri_string == 'budongchan/index'}class="current"{/if}><a href="{site_url('budongchan/index')}">不动产信息</a></li>{/if}
            {if isset($permission['budongchan/cs'])}<li {if $uri_string == 'budongchan/cs'}class="current"{/if}><a href="{site_url('budongchan/cs')}">初审</a></li>{/if}
            {if isset($permission['budongchan/fs'])}<li {if $uri_string == 'budongchan/fs'}class="current"{/if}><a href="{site_url('budongchan/fs')}">复审</a></li>{/if}
        </ul>
    </div>