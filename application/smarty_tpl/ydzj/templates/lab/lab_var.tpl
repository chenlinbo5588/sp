      <script>
      	var tree = null, isFounder = {if $lab_param['current']['oid'] == $profile['basic']['uid']}1{else}0{/if};
      	var labEditUrl = "{site_url('lab/edit')}",
      		labDeleteUrl = "{site_url('lab/delete')}",
      		labMoveUrl = "{site_url('lab/move')}",
      		treeXMLUrl = "{site_url('lab/getTreeXML')}",
      		treeImgUrl = "{$smarty.const.TREE_IMG_PATH}",
      		labUserDeleteUrl = "{site_url('lab/delete_lab_user')}",
            labManagerUrl = "{site_url('lab/manager_lab_user')}",
            labUserSearchUrl = "{site_url('lab_user/search') }";
       </script>
