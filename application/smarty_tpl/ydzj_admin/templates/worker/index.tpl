{include file="common/main_header_navs.tpl"}
  {config_load file="worker.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label for="name">{#name#}</label></th>
          <td><input type="text" value="{$smarty.get['name']|escape}" name="name" id="name" class="txt"></td>
          <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
      </tbody>
    </table>
  </form>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th>{#avatar#}</th>
          <th>{#name#}</th>
          <th>{#id_type#}</th>
          <th>{#id_no#}</th>
          <th>{#sex#}</th>
          <th>{#age#}</th>
          <th>{#jiguan#}</th>
          <th>{#mobile#}</th>
          <th>{#address#}</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
          <td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>
          <td class="w60 picture"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}"><img class="size-100x100" src="{if $item['avatar_s']}{resource_url($item['avatar_s'])}{else if $item['avatar_b']}{resource_url($item['avatar_b'])}{else if $item['avatar_m']}{resource_url($item['avatar_m'])}{else}{resource_url('img/default.jpg')}{/if}"/></a></td>
          <td class="name"><a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">{$item['name']|escape}</a></td>
         <td>{$basicData[$item['id_type']]['show_name']}</td>
         <td>{mask_string($item['id_no'])}</td>
         <td>{if $item['sex'] == 1}男{else}女{/if}</td>
         <td>{$item['age']}</td>
         <td>{$basicData[$item['jiguan']]['show_name']}</td>
         <td>{$item['mobile']}</td>
         <td>{$item['address']|escape}</td>
         <td>
          	<p>
          		{if isset($permission[$moduleClassName|cat:'/detail'])}<a href="{admin_site_url($moduleClassName|cat:'/detail')}?id={$item['id']}">详情</a>{/if}&nbsp;
          		{if isset($permission[$moduleClassName|cat:'/edit'])}<a href="{admin_site_url($moduleClassName|cat:'/edit')}?id={$item['id']}">编辑</a>{/if}
          	</p>
          	<p>
          		{if $item['yuesao_id']}{if isset($permission['yuesao/edit'])}<a href="{admin_site_url('yuesao/edit')}?id={$item['yuesao_id']}">月嫂</a>&nbsp;{/if}{else}{if isset($permission['yuesao/add'])}<a href="{admin_site_url('yuesao/add')}?worker_id={$item['id']}">月嫂入驻</a>&nbsp;{/if}{/if}
          		{if $item['baomu_id']}{if isset($permission['baomu/edit'])}<a href="{admin_site_url('baomu/edit')}?id={$item['baomu_id']}">保姆</a>&nbsp;{/if}{else}{if isset($permission['baomu/add'])}<a href="{admin_site_url('baomu/add')}?worker_id={$item['id']}">保姆入驻</a>&nbsp;{/if}{/if}
          		{if $item['hugong_id']}{if isset($permission['hugong/edit'])}<a href="{admin_site_url('hugong/edit')}?id={$item['hugong_id']}">护工</a>&nbsp;{/if}{else}{if isset($permission['hugong/add'])}<a href="{admin_site_url('hugong/add')}?worker_id={$item['id']}">护工入驻</a>&nbsp;{/if}{/if}
          	</p>
          </td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    
    <div class="fixedOpBar">
    	<label><input type="checkbox" class="checkall" id="checkallBottom" name="chkVal">全选</label>&nbsp;
         {if isset($permission[$moduleClassName|cat:'/delete'])}<a href="javascript:void(0);" class="btn deleteBtn" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/delete')}"><span>删除</span></a>{/if}
         {include file="common/pagination.tpl"}
     </div>
  </form>
  
<script>
$(function(){
    {if isset($permission[$moduleClassName|cat:'/delete'])}bindDeleteEvent();{/if}
});
</script>

{include file="common/main_footer.tpl"}