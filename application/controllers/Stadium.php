<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium extends Ydzj_Controller {
	
	//public $_controllerUrl;
	
	private $_urlParam = null;
	private $_stadiumid = 0;
	
	public function __construct(){
		parent::__construct();
		//$this->_controllerUrl = admin_site_url();
        
        $this->load->library('Stadium_Service');
        
        $this->_urlParam = $this->uri->segment_array();
	}
	
	
	public function index()
	{
		
		$title = '场馆';
		$city_id = $this->_getCity();
		if($city_id){
			$cityInfo = $this->stadium_service->getCityById($city_id);
		}
		
		//print_r($cityInfo);
		
		$title = '场馆';
		$this->setTopNavTitle($title);
		
		$searchKey = $this->input->get('search_key');
		if($searchKey){
			$condition['like']['title'] = $searchKey;
		}
		
		if($city_id){
			$condition['where'] = array('d'.$cityInfo['level'] => $city_id);
		}else{
			$cityInfo['id'] = 0;
			$cityInfo['level'] = 0;
			$cityInfo['name'] = '全国';
		}
		
		$list = $this->stadium_service->getPagerData($condition);
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('team/switch_city/upid/'.$city_id).'" title="点击切换城市">'.$cityInfo['name'].'</a>');
		$this->setRightNavLink('<a id="rightBarLink" class="bar_button" href="'.site_url('stadium/add').'">+添加场馆</a>');
		
		$this->setTopNavTitle($title);
		$this->seo('篮球馆，体育场馆');
		
		$this->assign('list',$list);
		$this->display('stadium/index');
	}
	
	
	/**
	 * 
	 */
	private function _extraStadiumInfo($stadium){
		
		
		
		
		$inManageMode = false;
		$canManager = $this->stadium_service->isManager($stadium,$this->_profile['memberinfo']);
		
		if($canManager){
			if($this->uri->segment(4) == 'manage'){
				$inManageMode = true;
				$this->assign('inManageMode',$inManageMode);
				$this->assign('mangeText','退出管理');
				$this->assign('editUrl',site_url('stadium/detail/'.$stadium['basic']['stadium_id']));
			}else{
				$this->assign('mangeText','管理场馆');
				$this->assign('editUrl',site_url('stadium/detail/'.$stadium['basic']['stadium_id'].'/manage/'));
			}
		}
		
		if($inManageMode){
			/*
			$this->load->library('Sports_Service');
			$poistionList = $this->sports_service->getMetaByCategoryAndGroup($team['basic']['category_name'],'位置');
			$roleList = $this->sports_service->getMetaByCategoryAndGroup($team['basic']['category_name'],'职务');
			$this->assign('positionList',$poistionList);
			$this->assign('roleList',$roleList);
			*/
		}
		
		$this->assign('canManager',$canManager);
		$this->assign('stadium',$stadium);
		
		$this->seoTitle($stadium['basic']['title']);
	}
	
	
	private function _prepareDetailData($id){
		$stadium = $this->stadium_service->getStadiumInfo($id);
		$this->_extraStadiumInfo($stadium);
	}
	
	
	public function detail(){
		$feedback = '';
		
		$this->_stadiumid = $this->_urlParam[3];
		
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('stadium').'" title="返回">返回</a>');
		//$this->setRightNavLink('<a id="rightBarLink" class="bar_button" href="'.site_url($this->uri->uri_string()).'">+收藏</a>');
		
		if($this->isPostRequest()){
			
			for($i = 0; $i < 1; $i++){
				if(!$this->isLogin()){
					$this->assign('returnUrl',site_url($this->uri->uri_string()));
					$this->display('member/login');
					
					break;
				}
				
				$stadium = $this->stadium_service->getStadiumInfo(intval($this->_stadiumid));
				$canManager = $this->stadium_service->isManager($stadium,$this->_profile['memberinfo']);
				
				if(!$canManager){
					$feedback = '对不起，您不是馆主无法管理';
					break;
				}
			}
			
			if($feedback){
				$this->assign('feedback','<div class="warning">'.$feedback.'</div>');
			}
			
			$this->_extraStadiumInfo($stadium);
			$this->display('stadium/detail');
			
		}else{
			
			$this->_prepareDetailData(intval($this->_stadiumid));
			$this->display('stadium/detail');
		}
		
		
	}
	
	/**
	 * 添加篮球场馆
	 */
	public function add(){
        
        if(!$this->isLogin()){
        	
        	$this->assign('returnUrl',site_url('stadium/add'));
        	$this->display('member/login');
        	
        }else{
        	
        	$this->assign('maxOtherFile',range(0,5));
        	$sportsCategoryList = $this->stadium_service->getSportsCategory();
        	
			$this->assign('sportsCategoryList',$sportsCategoryList);
			
        	$allMetaGroups = $this->stadium_service->getAllMetaGroups();
	        //print_r($allMetaGroups);
	        $this->assign('allMetaGroups',$allMetaGroups);
	        
			$this->seo('添加体育场馆');
	        
	        if($this->isPostRequest()){
	            //$this->form_validation->set_error_delimiters('<div class="error">','</div>');
	            for($i = 0 ; $i < 1; $i++){
	            	$this->_commonRules();
	            	$this->load->library('Attachment_Service');
	            	$fileUpload = array();
	            	$fileInfo = $this->attachment_service->addImageAttachment('cover_img');
	            	
	            	$this->load->helper('img');
	            	
	            	$images = array();
	            	
	            	for($i = 0; $i <= 5; $i++){
		            	$otherFile = $this->attachment_service->addImageAttachment('other_img'.$i);
		            	if($otherFile){
		            		$images[] = array(
		            			'id' => $otherFile['id'],
		            			'avatar' => $otherFile['file_url'],
		            			'avatar_large' => $otherFile['img_large'],
		            			'avatar_big' => $otherFile['img_big'],
		            			'avatar_middle' => $otherFile['img_middle'],
		            			'avatar_small' => $otherFile['img_small']
		            		);
		            		
		            		//对已上传的图片 在提交校验错误的情况下，显示预览
		            		$fileUpload['other_img'][$i] = array(
		            			'id' => $otherFile['id'],
		            			'url' => $otherFile['file_url'],
		            			'preview' => $otherFile['img_small']
		            		);
		            		
		            	}else{
		            		$otherImgUrl = $this->input->post('other_img'.$i.'_url');
		            		if(!empty($otherImgUrl)){
		            			$otherFile = array_merge(array('id' => $this->input->post('other_img'.$i.'_id')),getImgPathArray($otherImgUrl));
		            			$images[] = $otherFile;
		            			
		            			$fileUpload['other_img'][$i] = array(
									'id' => $otherFile['id'],
		            				'url' => $otherFile['avatar'],
		            				'preview' => $otherFile['avatar_small']
		            			);
		            		}
		            	}
		            }
			        
			        $this->assign('fileUpload',$fileUpload);
			        
		            if($this->form_validation->run() == false){
			            break;
		            }
		            
		            foreach($sportsCategoryList as $cate){
		            	if($cate['id'] == $this->input->post('category_id')){
		            		$_POST['category_name'] = $cate['name'];
		            		break;
		            	}
		            }
		            
	                $id = $this->stadium_service->addStadium($_POST,$images , $this->_profile['memberinfo']);
	                
	                if($id > 0){
	                	redirect('stadium/detail/'.$id);
	                	/*
	                    $this->assign('feedback','<div class="warning">场馆创建，请刷新页面重新尝试。</div>');
			        	$this->_prepareDetailData(intval($this->_stadiumid));
						$this->display('stadium/detail');
	                	*/
	                }else{
	                	$this->assign('feedback','<div class="warning">场馆创建失败，请刷新页面重新尝试。</div>');
	                	$this->display('stadium/add');
	                }
	            }
	        }else{
	        	$this->display('stadium/add');
	        }
        }
        
	}
    
    private function _commonRules(){
        
        $this->load->model('Sports_Category_Model');
        $this->form_validation->set_rules('category_id','场馆分类',array(
				'required',
				'is_natural_no_zero',
				array(
					'category_callable',
					array(
						$this->Sports_Category_Model,'avaiableCategory'
					)
				)
			),
			array(
				'category_callable' => '%s无效'
			)
		);

        $this->form_validation->set_rules('title','场馆名称',array('required'),
            array(
                'required' => '请输入场馆名称'
            )
        );
        
        $this->form_validation->set_rules('address','场馆地址',array('required'),
            array(
                'required' => '请标记场馆位置'
            )
        );
        
    }
    
    
    
    
}
