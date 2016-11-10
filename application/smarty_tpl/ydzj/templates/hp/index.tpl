{include file="common/my_header.tpl"}
	{config_load file="hp.conf"}
	<form action="{site_url($uri_string)}" method="post" id="formSearch">
        <input type="hidden" name="page" value=""/>
        <div class="goods_search">
        	 <textarea name="gc" placeholder="输入{#goods_code#}，每行一个货号或者单行按逗号分隔，一次最多可同时50个">{$smarty.post.gc}</textarea>
        	 {*<textarea name="kw" placeholder="输入{#goods_code#}，每行一个货号或者单行按逗号分隔，一次最多可同时50个">{$smarty.post.kw}</textarea>*}
             <ul class="search_con clearfix">
                <li>
                    <label class="ftitle">{#goods_name#}</label>
                    <input type="text" class="mtxt" name="gn" value="{$smarty.post.gn}"/>
                </li>
                <li>
                    <label class="ftitle">{#goods_size#}</label>
                    <input type="text" name="s1" class="stxt" value="{$smarty.post.s1}" placeholder="尺寸下限"/>
                    <input type="text" name="s2" class="stxt" value="{$smarty.post.s2}" placeholder="尺寸上限"/>
                </li>
                <li>
                    <label class="ftitle">{#sex#}</label>
                    <select name="sex">
                        <option value="0" {if $smarty.post.sex == 0}selected{/if}>不限</option>
                        <option value="1" {if $smarty.post.sex == 1}selected{/if}>男</option>
                        <option value="2" {if $smarty.post.sex == 2}selected{/if}>女</option>
                    </select>
                </li>
                {*
                <li>
                    <label class="ftitle">{#price_max#}</label>
                    <input type="text" name="pr1" class="stxt" value="{if $smarty.post.pr1}{$smarty.post.pr1}{/if}" placeholder="下限"/>
                    <input type="text" name="pr2" class="stxt" value="{if $smarty.post.pr2}{$smarty.post.pr2}{/if}" placeholder="上限"/>
                </li>
                *}
                <li>
                	<label class="ftitle">{#mtime#}</label>
                	<select name="mtime">
                	{foreach from=$mtime item=item key=key}
                		<option value="{$key}" {if $smarty.post.mtime == $key}selected{/if}>{$key}</option>
                	{/foreach}
                	</select>
                </li>
                <li>
                    <input class="master_btn" type="submit" name="search" value="查询"/>
                </li>
             </ul>
             {include file="hp/code_tip.tpl"}
	        <div>{include file="common/pagination.tpl"}</div>
	        {include file="./list.tpl"}
		    <div>{include file="common/pagination.tpl"}</div>
	    </div>
    </form>
    <script>
    $(function(){
    	$(document).tooltip({
    		items: "textarea[name=gc]",
      		content: function() {
      			return $("#gctip").html();
      		}
    	});
    });
    </script>
{include file="common/my_footer.tpl"}

