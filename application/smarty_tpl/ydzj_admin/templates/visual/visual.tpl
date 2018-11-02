{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search" >
		<tr>
	          <td>报表范围:</td>
	    	   <td>
	    		<input type="text" autocomplete="off"  value="{$search['date_s']}" name="date_s" id="date_s" {if $report_mode == '每日报表'} class="datepicker txt-short"{/if}/>
	    		{if  '每周报表' == $report_mode}周{else if '每月报表' == $report_mode}月{else if '每年报表' == $report_mode}年{/if}
	    		<input type="text" autocomplete="off"  value="{$search['date_e']}" name="date_e" id="date_e" {if $report_mode == '每日报表'} class="datepicker txt-short"{/if}/>
          		 {if  '每周报表' == $report_mode}周{else if '每月报表' == $report_mode}月{else if '每年报表' == $report_mode}年{/if}
          		</td>
			    {if '每周报表' == $report_mode || '每月报表' == $report_mode}
	          	<th><label for="year">报表年份</label></th>
	          	<td><input class="txt" name="year" id="year" value="{$search['year']}" type="text"></td>
			    {/if}
		  <td>请选择小区:</td>
        	<td colspan="2">
        		<select name="resident_id">
        			{foreach from=$residentList item=item}
        			<option value="{$item['id']}" {if $residentId == $item['id']}selected{/if}>{$item['name']|escape}</option>
        			{/foreach}
        		</select>
	         </td>
		  <td colspan="2"><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
		</tr>
	</table>
    <div id="top" style="height:400px;"></div>
      <script type="text/javascript" src="{resource_url('js/echarts.min.js')}"></script>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('top'));

        // 指定图表的配置项和数据
		option = {
		    title:{
	        	text: '{$residentName}' + '{$start_date}' + '至' + '{$end_date}' + '{$report_mode}',
	    		 x:'center',
        		 y:'bottom'
	    	},
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['物业费','能耗费','总计']
		    },
	        grid: {
		        left: '3%',
		        right: '4%',
		        bottom: '10%',
		        containLabel: true
		    },
		    xAxis: {
		        type: 'category',
		        data: {$dateJson}
		    },
		    yAxis : [{
			    type : 'value',
			    axisLabel : {
				    formatter: function(value){
				    return value}
			    }
		    }],
	        dataZoom: [{
			    type: 'inside',
			    start: 0,
			    end: 10
			}, {
			    start: 0,
			    end: 10,
			    handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
			    handleSize: '80%',
			    handleStyle: {
			        color: '#fff',
			        shadowBlur: 3,
			        shadowColor: 'rgba(0, 0, 0, 0.6)',
			        shadowOffsetX: 2,
			        shadowOffsetY: 2
			    }
			}],
		    series: [
		    {
		    	name:'物业费',
		        data: {$wuyeDatejson},
		        type: 'line'
		    },
	        {
	        	name:'能耗费',
		        data:  {$nenghaoDatejson},
		        type: 'line'
		    },
		            {
	        	name:'总计',
		        data:  {$sumjson},
		        type: 'line'
		    }
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