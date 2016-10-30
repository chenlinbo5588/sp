{include file="common/my_header.tpl"}
  {config_load file="lab.conf"}
  	<div>
  	   {if isset($permission['lab/add'])}<a class="master_btn" href="{site_url('lab/add')}">添加实验室</a>{/if}
  	</div>
  	<div class="w-tixing clearfix"><b>温馨提醒：</b>
        <p>1、【修改操作】鼠标双击实验室名称进入修改页面。</p>
        <p>2、【删除操作】鼠标左键拖曳实验室名称至右侧空白区域然后释放即可进行删除.</p>
        <p>3、【改变父级】鼠标拖曳实验室名称到某一实验室下。</p>
        <p>4、【排序调整】鼠标拖曳实验室名称至其父级节点下，则当前实验室则显示在最前面， </p>
      </div>
      <div id="catelist">
        <div class="rounded_box" style="min-height:100px;">
          <div id="treeboxbox_tree1"></div>
          <div id="loading_img" class="loading_div" style="display:none;"></div>
        </div>
      </div>
      <div id="dialog-confirm" title="删除{#title#}" style="display:none;"><p><span class="ui-icon ui-icon-alert" style="float:left;"></span>确定要移除<span class="categoryName hightlight"></span>吗?</p></div>
	 {include file="common/dhtml_tree.tpl"}
	 {include file="common/jquery_ui.tpl"}
      {include file="./lab_var.tpl"}
       <script type="text/javascript" src="{resource_url('js/lab/index.js')}"></script>
{include file="common/my_footer.tpl"}