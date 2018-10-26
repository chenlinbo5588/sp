{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search" >
		<tr class="noborder">
          <td colspan="2">{#resident_name#}:</td>
        </tr>
        <tr class="noborder">
          <td class="vatop" colspan="2">
          	<ul class="ulListStyle1 clearfix">
  			{foreach from=$residentList key=Key item=Item}
  			<li><label><input type="checkbox" name="resident_id[]" value="{$Item['id']}" {if $search['resident_id']}{if in_array($Item['id'],$search['resident_id'])}checked="checked"{/if}{/if}/>{$Item['name']|escape}</label></li>	
  			{/foreach}
          	</ul>
          </td>
        	<th><label for="year">报表年份</label></th>
          	<td><input class="txt" name="year" id="year" value="{$search['year']}" type="text"></td>
		  <td colspan="2"><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
		</tr>
	</table>
    <div id="top" style="height:400px;"></div>
      <script type="text/javascript" src="{resource_url('js/echarts.js')}"></script>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('top'));

        // 指定图表的配置项和数据


		option = {
		    color: ['#3398DB'],
		    tooltip : {
		        trigger: 'axis',
		        axisPointer : {            
		            type : 'line'       
		        }
		    },
		    legend: {
		        data: ['物业费','能耗费','总计'],
		    },
		    grid: {
		        left: '3%',
		        right: '4%',
		        bottom: '3%',
		        containLabel: true
		    },
		    xAxis : [
		        {
		            type : 'category',
		            data: {$dateJson},
		            axisTick: {
		                alignWithLabel: true
		            }
		        }
		    ],
		    yAxis : [
		        {
		            type : 'value'
		        }
		    ],
		    series : [
				    {
				    	name:'物业费',
				        data: {$wuyeDatejson},
				        type: 'bar',
				        barWidth:'40', 
				        color:'#819FF7'
				    },
			        {
			        	name:'能耗费',
				        data:  {$nenghaoDatejson},
				        type: 'bar',
			       		barWidth:'40', 
				        color:'#F5A9F2'
				    },
				            {
			        	name:'总计',
				        data:  {$sumjson},
				        type: 'bar',
		           		barWidth:'40', 
				        color:'#848484'
				    },
		    ]
		};
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
    <script>
$(function(){

     $( ".datepicker" ).datepicker({
    	changeYear: true
    });
    
});
</script>
{include file="common/main_footer.tpl"}