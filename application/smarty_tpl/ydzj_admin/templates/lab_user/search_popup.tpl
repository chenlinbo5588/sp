               <div class="form">
                    <div class="clearfix">
                      <label>名称:</label>
                      <input type="text" class="form_input" id="search_username" name="username" value="{$smarty.get.username}" placeholder="请输入实验员名称" />
                      <input type="button" class="msbtn" id="search_btn" value="查询" />
                    </div>
                </div>
                <div id="usertable">
                    {include file="./search.tpl"}
			    </div>
