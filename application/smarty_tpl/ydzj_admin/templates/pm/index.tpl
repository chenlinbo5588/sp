{include file="common/main_header_navs.tpl"}
  
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  <input type="hidden" name="page" value="{$currentPage}"/>
  <div class="fixedBar">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
	      <td>创建时间:</td>
	      <td>
	    		从 <input type="text" value="{$search['date_s']}" name="date_s" value="" class="datepicker txt-short"/>
	    		-
	    		到 <input type="text" value="{$search['date_e']}" name="date_e" value="" class="datepicker txt-short"/>
	      </td>
	      <td><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
        </tr>
      </tbody>
    </table>
  </div>
  </form>
    <table class="table tb-type2 mgbottom">
      <thead>
        <tr class="thead">
          {if $showSetRead}<th class="w24">选择</th>{/if}
          <th>标题</th>
          <th>时间</th>
        </tr>
      </thead>
      <tbody>
      	{foreach from=$list['data'] item=item}
      	<tr class="hover edit" id="row{$item['id']}">
         {if $showSetRead}<td><input type="checkbox" name="id[]" group="chkVal" value="{$item['id']}" class="checkitem"></td>{/if}
         <td><a class="popwin" href="javascript:void(0);" data-url="{admin_site_url($moduleClassName|cat:'/detail')}?id={$item['id']}">{$item['title']|escape}</a></td>
         <td>{$item['gmt_create']|date_format:"%Y-%m-%d %H:%M:%S"}</td>
        </tr>
        {/foreach}
      </tbody>
    </table>
    <div class="fixedOpBar">
    	{if $showSetRead}
    	<label><input type="checkbox" class="checkall" name="chkVal">全选</label>&nbsp;
    	<a href="javascript:void(0);" class="btn opBtn" data-title="设为已读" data-checkbox="id[]" data-url="{admin_site_url($moduleClassName|cat:'/setread')}"><span>设为已读</span></a>
        {/if}
        {include file="common/pagination.tpl"}
    </div>
  </form>
  <div id="pmDetailDlg" title="站内信详情"></div>
  <script type="text/javascript" src="{resource_url('js/pm/admin_pm_index.js',true)}"></script>
{include file="common/main_footer.tpl"}