{include file="common/my_header.tpl"}
  {form_open(site_url($uri_string),'name="formSearch" id="formSearch"')}
    <input type="hidden" name="page" value=""/>
    <div class="goods_search">
     <ul class="search_con clearfix">
        <li>
            <label class="ftitle">名称</label>
            <input type="text" value="{$smarty.post['name']}" name="name" class="txt" placeholder="请输入分类称"/>
        </li>
        <li>
            <input class="master_btn" type="submit" name="search" value="查询"/>
        </li>
     </ul>
     <a class="master_btn sideadd" href="{site_url('lab_gcate/add')}">添加分类</a>
    </div>
    <table class="fulltable">
      
      <thead>
        <tr class="thead">
          <th class="w25pre">分类名称</th>
          <th>创建时间</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        {foreach from=$list item=item}
        <tr class="hover" id="row{$item['id']}">
          <td>{str_repeat('----',$item['level'])}{$item['name']|escape}</td>
          <td>{time_tran($item['gmt_create'])}</td>
          <td class="align-center">
            <a href="{site_url('lab_gcate/edit')}?id={$item['id']}">编辑</a>&nbsp;
            <a class="delete" href="javascript:void(0);" data-id="{$item['id']}" data-url="{site_url('lab_gcate/delete?id=')}{$item['id']}" data-title="{$item['name']|escape}">删除</a>
          </td>
        </tr>
        {foreachelse}
        <tr class="no_data">
          <td colspan="5">没有符合条件的记录</td>
        </tr>
        {/foreach}
      </tbody>
    </table>
  </form>
  <script type="text/javascript">
    $(function(){
        bindDeleteEvent();
    });
  </script>
{include file="common/my_footer.tpl"}