{include file="common/my_header.tpl"}
    {config_load file="hp.conf"}
    <style>
    label.error {
    	display:block;
    }
    
    #bodyContent .fulltable {
        float:left;
        position:relative;
        margin:5px;
    }
    </style>
    <div id="pubwrap">
        {include file="./pub_tip.tpl"}
		<form action="{site_url($uri_string)}" method="post" id="pubForm">
			<div>
	            <input type="button" name="addrow" class="action" value="增加一行"/>
	            <input type="button" name="clearall" class="action" value="清空"/>
	            <input type="submit" name="tijiao" class="master_btn" value="发布"/>
	            
	            <span id="addstat">当前:<strong>{count($initRow)}</strong>个求货</span>
	        </div>
	        
	        <div id="bodyContent">
		        {foreach from=$initRow item=item}
		        <table class="fulltable border style2 noext">
		            <thead>
		              <tr>
		                  <th>列名</th>
		                  <th>内容</th>
		              </tr>
		            </thead>
		            <tbody>
		                <tr>
		                    <td><label class="required"><em>*</em>序号</label></td>
		                    <td>{$item + 1}</td>
		                </tr>
		                <tr>
		                    <td><label class="required"><em>*</em>{#goods_code#}</label></td>
				            <td><input type="text" name="goods_code[]" value="{$postData['goods_code'][$item]|escape}" title="只允许英文字母数字下划线、破折号,最长15个字符" placeholder="请输入货号"/>{form_error('goods_code'|cat:$item)}</td>
				        </tr>
				        <tr>
				            <td><label class="required"><em>*</em>{#goods_name#}</label></td>
				            <td><input type="text" name="goods_name[]" value="{$postData['goods_name'][$item]|escape}" title="可自定义名称,中文或英文,最长15个字符" placeholder="请输入货品名称"/>{form_error('goods_name'|cat:$item)}</td>
				        </tr>
                        <tr>
                            <td><label class="required"><em>*</em>{#goods_color#}</label></td>
				            <td><input type="text" name="goods_color[]" value="{$postData['goods_color'][$item]|escape}" placeholder="如:白"/>{form_error('goods_color'|cat:$item)}</td>
				        </tr>
				        <tr>
				            <td><label class="required"><em>*</em>{#goods_size#}</label></td>
				            <td>
				             <a class="fa fa-long-arrow-down" href="javascript:void(0)" ></a>
				             <input type="text" name="goods_size[]" title="{#size_example#}" value="{$postData['goods_size'][$item]|escape}" placeholder="{#size_example#}"/>
				             <a href="javascript:void(0)" class="fa fa-long-arrow-up"></a>{form_error('goods_size'|cat:$item)}
				            </td>
				        </tr>
				        <tr>
				            <td><label class="required"><em>*</em>{#quantity#}</label></td>
				            <td><input type="text" name="quantity[]" value="{if $postData['quantity'][$item]}{$postData['quantity'][$item]|escape}{else}1{/if}" placeholder="请输入{#quantity#}"/>{form_error('quantity'|cat:$item)}</td>
				        </tr>
				        <tr>
				            <td><label class="required"><em>*</em>{#sex#}</label></v>
				            <td>
				                <select class="w60" name="sex[]">
				                    <option value="1" {if $postData['sex'][$item] == 1}selected{/if}>男</option>
				                    <option value="2" {if $postData['sex'][$item] == 2}selected{/if}>女</option>
				                </select>{form_error('sex'|cat:$item)}
				            </td>
				        </tr>
				        <tr>
				            <td><label class="required"><em>*</em>{#price_max#}</label></td>
				            <td><input type="text" name="price_max[]" title="{#price_see#}" value="{$postData['price_max'][$item]|escape}" placeholder="{#price_max#}"/>{form_error('price_max'|cat:$item)}</td>
				        </tr>
				        <tr>
				            <td>{#price_status#}</td>
				            <td><input type="checkbox" name="price_status[]" title="{#price_cansee#}" {if $postData['price_status'][$item] == 1}checked{/if} value="1" placeholder="{#price_status#}"/>{form_error('price_status'|cat:$item)}</td>
				        </tr>
				        <tr>
				            <td>{#send_zone#}</td>
				            <td><input type="text" name="send_zone[]" value="{$postData['send_zone'][$item]|escape}" placeholder="请输入{#send_zone#}"/>{form_error('send_zone'|cat:$item)}</td>
				        </tr>
				        <tr>
                            <td>{#send_day#}</td> 
				            <td><input type="text" name="send_day[]" class="datepicker" value="{$postData['send_day'][$item]|escape}" placeholder="{#send_day#}"/>{form_error('send_day'|cat:$item)}</td>
				        </tr>
				        <tr>
				            <td>{#op#}</td>
				            <td>
				                 <a class="incre copyrow" href="javascript:void(0);">+1码</a>&nbsp;
				                 <a class="decre copyrow" href="javascript:void(0);">-1码</a>&nbsp;
				                 <a class="deleterow" href="javascript:void(0);">取消</a>
				            </td>
				        </tr>
		            </tbody>
		        </table>
	            {/foreach}
	         </div>
	    </form>
    </div>
    <script type="text/x-template" id="rowTpl">
        <table class="fulltable border style2 noext">
            <thead>
              <tr>
                  <th>列名</th>
                  <th>内容</th>
              </tr>
            </thead>
            <tbody>
		        <tr>
		            <td><label class="required"><em>*</em>序号</label></td>
		            <td></td>
		        </tr>
		        <tr>
		            <td><label class="required"><em>*</em>{#goods_code#}</label></td>
		            <td><input type="text" name="goods_code[]" value="" title="只允许英文字母数字下划线、破折号,最长15个字符" placeholder="请输入货号"/></td>
		        </tr>
		        <tr>
		            <td><label class="required"><em>*</em>{#goods_name#}</label></td>
		            <td><input type="text" name="goods_name[]" value="" title="可自定义名称,中文或英文,最长15个字符" placeholder="请输入货品名称"/></td>
		        </tr>
		        <tr>
		            <td><label class="required"><em>*</em>{#goods_color#}</label></td>
		            <td><input type="text" name="goods_color[]" value="" placeholder="如:白"/></td>
		        </tr>
		        <tr>
		            <td><label class="required"><em>*</em>{#goods_size#}</label></td>
		            <td>
		                <a class="fa fa-long-arrow-down" href="javascript:void(0)" ></a>
		                <input type="text" class="" title="{#size_example#}" name="goods_size[]" value="" placeholder="{#size_example#}"/>
		                <a href="javascript:void(0)" class="fa fa-long-arrow-up"></a>
		           </td>
		        </tr>
		        <tr>
		            <td><label class="required"><em>*</em>{#quantity#}</label></td>
		            <td><input type="text" name="quantity[]" value="1" placeholder="请输入{#quantity#}"/></td>
		        </tr>
		        <tr>
		            <td><label class="required"><em>*</em>{#sex#}</label></td>
		            <td><select name="sex[]">
			                <option value="1">男</option>
			                <option value="2">女</option>
		            </select></td>
		        </tr>
		        <tr>
		            <td><label class="required"><em>*</em>{#price_max#}</label></td>
		            <td><input type="text" class="w60" name="price_max[]" title="{#price_see#}" value="" placeholder="{#price_max#}"/></td>
		        </tr>
		        <tr>
		            <td>{#price_status#}</td>
		            <td><input type="checkbox" name="price_status[]" title="{#price_cansee#}" value="1" placeholder="{#price_status#}"/></td>
		        </tr>
		        <tr>
		            <td>{#send_zone#}</td>
		            <td><input type="text" name="send_zone[]" value="" placeholder="请输入{#send_zone#}"/></td>
		        </tr>
		        <tr>
		            <td>{#send_day#}</td>
		            <td><input type="text" name="send_day[]" value="" class="w72 datepicker" placeholder="{#send_day#}"/></td>
		        </tr>
		        <tr>
		            <td>{#op#}</td>
		            <td>
		            	<a class="incre copyrow" href="javascript:void(0);">+1码</a>&nbsp;
					    <a class="decre copyrow" href="javascript:void(0);">-1码</a>&nbsp;
		            	<a class="deleterow" href="javascript:void(0);">删除</a>
		           	</td>
		        </tr>
		      </tbody>
		 </table>
    </script>
    <script>
    	var maxRow = {$maxRowPerReq};
    </script>
    <script type="text/javascript" src="{resource_url('js/jquery-ui/i18n/zh-CN.js')}"></script>
    <script type="text/javascript" src="{resource_url('js/my/hp.js',true)}"></script>
{include file="common/my_footer.tpl"}

