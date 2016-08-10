		<div class="sidebar" id="sidebar">
		    <h2>功能菜单</h2>
	        <ul>
	        
	            <li {if $act == 'add'}class="selected"{/if}><a href="{base_url('lab_category/add')}">添加类别</a></li>
	            {if $act == 'edit'}<li class="selected"><a href="{base_url('lab_category/edit/id/')}{$info['id']}">修改类别</a></li>{/if}
	        </ul>
	        
	        
	        
	    </div>
