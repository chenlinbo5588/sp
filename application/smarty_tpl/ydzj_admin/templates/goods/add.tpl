{include file="common/lab_admin_header.tpl"}
    <div class="submenu">
        <div class="clear"></div>
    </div>
    <style type="text/css">
    
    .form_row .lab_item {
        float:none;
        display:inline;
        margin: 5px;
    }
    
    </style>
    <div class="center_content"> 
	    <div id="right_wrap">
		    <div id="right_content">
		      {if $action == 'add'}
		      <form name="categoryForm" method="post" action="{base_url('lab_goods/add')}" onsubmit="return validation(this);">
		      {elseif $action = 'edit'}
		      <form name="categoryForm" method="post" action="{base_url('lab_goods/edit')}" onsubmit="return validation(this);">
		      <input type="hidden" name="id" value="{$info['id']}"/>
		      <input type="hidden" name="lab_id" value="{$info['lab_id']}"/>
		      {/if}
		       <div class="form">
		            <div class="form_row clearfix">
                      <label class="require"><em>*</em>实验室:</label>
                      {if $action == 'add'}
                      <select class="form_select"  name="lab_id">
                        <option value="">请选择</option>
                        {foreach from=$labList['data'] item=item}
                        <option value="{$item['id']}" {if $info['lab_id'] == $item['id']}selected{/if}>{$item['address']|escape}</option>
                        {/foreach}
                      </select>
                      {else}
                        {$info['lab_address']|escape}
                      {/if}
                      {form_error('lab_id')}
                    </div>
		            <div class="form_row clearfix">
                      <label class="require"><em>*</em>药品柜/<br/>试验台编号:</label>
                      <input type="text" class="form_input" name="code" style="width:250px;" value="{$info['code']|escape}" placeholder="请输入药品柜/试验台编号" />
                      {form_error('code')}
                      <span class="tip">药品柜/试验台编号，如A柜1层</span>
                    </div>
                    <div class="form_row clearfix">
                      <label class="require"><em>*</em>类别:</label>
                      <select class="form_select" name="category_id">
                          {foreach from=$categoryList item=item key=key}
                          <option value="{$key}" {if $key == $info['category_id']}selected{/if}>{$item['sep']}{$item['name']|escape}</option>
                          {/foreach}
                      </select>
                      {form_error('category_id')}
                      <span class="tip">如果找不到到对应类别,<a href="{base_url('lab_category/add')}">点击添加类别</a></span>
                    </div>
		            <div class="form_row clearfix">
		              <label class="require"><em>*</em>名称:</label>
		              <input type="text" class="form_input" name="name" style="width:250px;" value="{$info['name']|escape}" placeholder="请输入名称" />
		              {form_error('name')}
		            </div>
		            
                    <div class="form_row clearfix">
                      <label class="require"><em>*</em>单位:</label>
                      <select class="form_select" name="measure">
                          {foreach from=$measureList['data'] item=item key=key}
                          <option value="{$item['name']}" {if $item['name'] == $info['measure']}selected{/if}>{$item['name']|escape}</option>
                          {/foreach}
                      </select>
                      {form_error('category_id')}
                    </div>
                    
                    <div class="form_row clearfix">
                      <label class="">规格:</label>
                      <input type="text" class="form_input" name="specific" value="{$info['specific']|escape}" placeholder="请输入规格" />
                      {form_error('specific')}
                    </div>
                    <div class="form_row clearfix">
                      <label class="">药品CAS号:</label>
                      <input type="text" class="form_input" name="cas" value="{$info['cas']|escape}" placeholder="请输入药品CAS号" />
                      {form_error('cas')}
                    </div>
                    <div class="form_row clearfix">
                      <label class="">危险等级:</label>
                      <input type="text" class="form_input" name="danger_remark" value="{$info['danger_remark']|escape}" placeholder="请输入危险等级" />
                      {form_error('danger_remark')}
                      <span class="tip">例如: 剧毒品，易制毒，易爆，易腐蚀</span>
                    </div>
                    <div class="form_row clearfix">
                      <label class="">生产厂家:</label>
                      <input type="text" class="form_input" name="manufacturer" value="{$info['manufacturer']|escape}" placeholder="请输入生产厂家" />
                      {form_error('manufacturer')}
                    </div>
                    
                    <div class="form_row clearfix">
                      <label class="require"><em>*</em>参考价格:</label>
                      <input type="text" class="form_input" name="price" value="{$info['price']|escape}" placeholder="请输入价格" />
                      {form_error('price')}
                    </div>
                    
                    <div class="form_row clearfix">
                      <label class="require"><em>*</em>库存:</label>
                      <input type="text" class="form_input" name="quantity" value="{$info['quantity']|escape}" placeholder="请输入库存数量" />
                      {form_error('quantity')}
                    </div>
                    <div class="form_row clearfix">
                      <label class="">底库存预警<br/>(0为不报警):</label>
                      <input type="text" class="form_input" name="threshold" value="{$info['threshold']|escape}" placeholder="请输入阀值" />
                      {form_error('threshold')}
                    </div>
                    <div class="form_row clearfix">
                      <label class="">实验/课程名称:</label>
                      <input type="text" class="form_input" name="subject_name" value="{$info['subject_name']|escape}" placeholder="请输入实验/课程名称" />
                      {form_error('subject_name')}
                    </div>
                    <div class="form_row clearfix">
                      <label class="">备注:</label>
                      <input type="text" class="form_input" name="project_name" value="{$info['project_name']|escape}" placeholder="请输入备注" />
                      {form_error('project_name')}
                    </div>
                    <div class="form_row paddingleft100">
	                   <input type="submit" class="form_submit" value="保存" />
	                   {if $gobackUrl }<input type="hidden" name="gobackUrl" value="{$gobackUrl}"/><input type="button" class="form_submit" value="返回" onclick="location.href='{$gobackUrl}'" />{/if}
	               </div>
		        </div>
		        {if $action == 'add' || $action == 'edit'} </form>{/if}
		    </div><!-- end of right content-->
		</div><!-- end of right wrap -->
		<script>
		  function validation(form){
		         {if $action == 'add'}
                if($("select[name=lab_id]").val() == ''){
                    $.jBox.tip('请选择实验室', '提示');
                    
                    $("select[name=lab_id]").focus();
                    return false;
                }
                {/if}
                if($("input[name=code]").val() == ''){
                    $.jBox.tip('请输入药品柜/试验台编号:', '提示');
                    $("input[name=code]").focus();
                    return false;
                }
                
                if($("input[name=name]").val() == ''){
                    $.jBox.tip('请输入名称:', '提示');
                    $("input[name=name]").focus();
                    return false;
                }
                
                if($("input[name=price]").val() == ''){
                    $.jBox.tip('请输入参考价格', '提示');
                    return false;
                }
                
                return true;
            }
		
		  
		  {if $message}
		  $(function(){
		      {if $success }
			      {if $action == 'add'}
			      var submit = function (v, h, f) {
	                 if (v == 'ok'){
	                 
	                 }else if (v == 'cancel'){
	                     location.href = "{base_url('lab_goods')}";
	                 }
	                 return true; //close
	              };
	            
	              $.jBox.confirm("{$message},要继续添加吗？", "提示", submit);
	              {else}
	              $.jBox.success("{$message}", "提示");
	              {/if}
		      {else}
		      $.jBox.error('{$message}', '提示');
		      {/if}
		  });
		  {/if}
		</script>
		{include file="./side.tpl"} 
{include file="common/lab_admin_footer.tpl"}