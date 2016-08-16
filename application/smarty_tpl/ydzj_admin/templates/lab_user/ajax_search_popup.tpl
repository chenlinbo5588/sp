		      <div id="userlist">
	               <div class="form">
	                    <div class="clearfix">
	                      <label>名称:</label>
	                      <input type="text" class="form_input" id="search_username" name="username" value="{$smarty.get.username}" placeholder="请输入实验员名称" />
	                      <input type="button" class="form_submit" id="search_btn" value="查询" />
	                    </div>
	                </div>
	                <div id="usertable">
                        {include file="lab_user/ajax_search.tpl"}
				    </div>
			  </div>
			  <script>
			     $.loadingbar({ urls: [ new RegExp("{base_url('lab_user/search') }") ], templateData:{ message:"努力加载中..." } , container: "#userlist"  });
			  </script>
