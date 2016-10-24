		       <div class="form">
		            <div class="form_row clearfix">
                      <label class="require"><em>*</em>实验室地址:</label>
                      <input type="text" class="form_input" name="lab_address" style="width:250px;" value="{$info['lab_address']|escape}"  />
                    </div>
		            <div class="form_row clearfix">
                      <label class="require"><em>*</em>存放地址:</label>
                      <input type="text" class="form_input" name="code" style="width:250px;" value="{$info['code']|escape}"  />
                    </div>
                    <div class="form_row clearfix">
                      <label class="require"><em>*</em>类别:</label>
                      <input type="text" class="form_input" name="category_name" style="width:250px;" value="{$info['category_name']|escape}" />
                    </div>
		            <div class="form_row clearfix">
		              <label class="require"><em>*</em>名称:</label>
		              <input type="text" class="form_input" name="name" style="width:250px;" value="{$info['name']|escape}" />
		            </div>
                    <div class="form_row clearfix">
                      <label class="require"><em>*</em>单位:</label>
                      <input type="text" class="form_input" name="name" style="width:250px;" value="{$info['measure']|escape}"  />
                    </div>
                    <div class="form_row clearfix">
                      <label class="">规格:</label>
                      <input type="text" class="form_input" name="specific" value="{$info['specific']|escape}"  />
                    </div>
                    <div class="form_row clearfix">
                      <label class="">药品CAS号:</label>
                      <input type="text" class="form_input" name="cas" value="{$info['cas']|escape}" />
                    </div>
                    <div class="form_row clearfix">
                      <label class="">危险等级:</label>
                      <input type="text" class="form_input" name="danger_remark" value="{$info['danger_remark']|escape}" />
                    </div>
                    <div class="form_row clearfix">
                      <label class="">生产厂家:</label>
                      <input type="text" class="form_input" name="manufacturer" value="{$info['manufacturer']|escape}"  />
                    </div>
                    <div class="form_row clearfix">
                      <label class="require"><em>*</em>参考价格:</label>
                      <input type="text" class="form_input" name="price" value="{$info['price']|escape}" />
                    </div>
                    <div class="form_row clearfix">
                      <label class="require"><em>*</em>库存:</label>
                      <input type="text" class="form_input" name="quantity" value="{$info['quantity']|escape}" />
                    </div>
                    <div class="form_row clearfix">
                      <label class="">底库存预警<br/>(0为不报警):</label>
                      <input type="text" class="form_input" name="threshold" value="{$info['threshold']|escape}" />
                    </div>
                    <div class="form_row clearfix">
                      <label class="">实验名称/<br/>课程名称:</label>
                      <input type="text" class="form_input" name="subject_name" value="{$info['subject_name']|escape}"  />
                    </div>
                    <div class="form_row clearfix">
                      <label class="">备注:</label>
                      <input type="text" class="form_input" name="project_name" value="{$info['project_name']|escape}" />
                    </div>
		        </div>
		        