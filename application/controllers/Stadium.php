<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium extends Ydzj_Controller {
	
	//public $_controllerUrl;
	
	private $_urlParam = null;
	private $_stadiumid = 0;
	
	public function __construct(){
		parent::__construct();
		//$this->_controllerUrl = admin_site_url();
        
        $this->load->library('Stadium_service');
        
        $this->_urlParam = $this->uri->segment_array();
	}
	
	
	public function index()
	{
		
		$title = '场馆场地';
		$city_id = $this->_getCity();
		if($city_id){
			$cityInfo = $this->stadium_service->getCityById($city_id);
		}
		
		//print_r($cityInfo);
		
		$title = '场馆场地';
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
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('stadium/switch_city/upid/'.$city_id).'" title="点击切换城市">'.$cityInfo['name'].'</a>');
		$this->setRightNavLink('<a id="rightBarLink" class="bar_button" href="'.site_url('stadium/add').'">+发现场馆</a>');
		
		$this->setTopNavTitle($title);
		$this->seo('体育场馆','篮球馆,足球场,游泳馆,羽毛球场,网球场,体育场馆');
		
		
		$this->assign('cityLevel',$cityInfo['level']);
		$this->assign('list',$list);
		$this->display();
	}
	
	
	/**
	 * 切换城市
	 */
	public function switch_city(){
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button goback" href="'.site_url('stadium').'" title="返回场馆">场馆</a>');
		
		$ar = $this->uri->segment_array();
		
		if($this->isPostRequest()){
			$this->input->set_cookie('city',$ar[4],2592000);
			redirect('stadium');
		}
		
		$this->load->library('Common_District_service');
		if($ar[4]){
			$city = $ar[4];
			$cityInfo = $this->common_district_service->getDistrictInfoById($city);
		}else{
			$city = $ar[4];
			
			$cityInfo['id'] = $city;
			$cityInfo['name'] = '全国';
		}
		
		$childCity = $this->common_district_service->getDistrictByPid($city);
		
		$title = $cityInfo['name'];
		$this->setTopNavTitle($title);
		$this->seoTitle($title);
		
		$this->assign('cityUrl','stadium/switch_city/upid/');
		$this->assign('formUrl',site_url('stadium/switch_city/upid/'.$cityInfo['id']));
		$this->assign('currentCity',$cityInfo);
		$this->assign('cityList',$childCity);
		$this->display('common/switch_city');
	}
	
	
	
	
	
	/**
	 * 
	 */
	private function _extraStadiumInfo($stadium){
		
		$inManageMode = false;
		$canManager = $this->stadium_service->isManager($stadium,$this->_profile['basic']);
		
		$this->assign('openType',$this->stadium_service->getOpenType());
		
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
			$this->assign('maxOtherFile',range(0,5));
			$stadiumOwnerList = $this->stadium_service->getStadiumMeta(array('where' => array('gname' => '权属')));
			$this->assign('stadiumOwnerList',$stadiumOwnerList);
	        
	        
	        if($this->isGetRequest()){
	        	foreach($stadium['photos'] as $key => $photo ){
		        	$fileUpload[$key] = array(
						'aid' => $photo['aid'],
						'url' => $photo['avatar']
					);
		        }
		        
		        $this->assign('fileUpload',$fileUpload);
	        }
		}
		
		
		$this->assign('formTarget',$this->uri->uri_string());
		$this->assign('canManager',$canManager);
		$this->assign('stadium',$stadium);
		
		$this->setTopNavTitle(cutText($stadium['basic']['title'],8));
		$this->seoTitle($stadium['basic']['title']);
	}
	
	
	private function _prepareDetailData($id){
		$stadium = $this->stadium_service->getStadiumInfo($id);
		$this->_extraStadiumInfo($stadium);
	}
	
	
	public function detail(){
		$feedback = '';
		$manageOk = false;
		
		$this->_stadiumid = $this->_urlParam[3];
		
		
		$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('stadium').'" title="返回">返回</a>');
		//$this->setRightNavLink('<a id="rightBarLink" class="bar_button" href="'.site_url($this->uri->uri_string()).'">+收藏</a>');
		
		if($this->isPostRequest()){
			
			$this->assign('inPost',true);
			for($i = 0; $i < 1; $i++){
				if(!$this->isLogin()){
					$this->assign('returnUrl',site_url($this->uri->uri_string()));
					$this->display('member/login');
					
					break;
				}
				
				$stadium = $this->stadium_service->getStadiumInfo(intval($this->_stadiumid));
				$canManager = $this->stadium_service->isManager($stadium,$this->_profile['basic']);
				
				if(!$canManager){
					$feedback = '对不起，您不是场馆管理员';
					break;
				}
				
				$this->_commonRules();
				$dealData = $this->_dealPicture();
			    $fileUpload = $dealData['currentImg'];
			        
				$this->assign('fileUpload',$fileUpload);
				
				if($this->form_validation->run() == false){
	            	if(empty($fileUpload[0]['aid'])){
	            		$this->assign('img_error0','请上传场馆封面照片,JPG格式');
	            	}
	            	
	            	break;
	            }else{
	            	//必须传一张封面
	            	if(empty($fileUpload[0]['aid'])){
	            		$this->assign('img_error0','请上传场馆封面照片,JPG格式');
	            		break;
	            	}
	            }
	            
	            
	            $row = $this->stadium_service->editStadium($stadium,$_POST,$dealData['currentImg'],$this->_profile['basic']);
	            $feedback = '修改成功';
	            
	            $manageOk = true;
			}
			
			
			if($manageOk){
				$this->_prepareDetailData(intval($this->_stadiumid));
				$this->assign('feedback','<div class="success">'.$feedback.'</div>');
			}else{
				$this->_extraStadiumInfo($stadium);
				$this->assign('feedback','<div class="warning">'.$feedback.'</div>');
			}
			
			$this->display('stadium/detail');
			
		}else{
			
			$this->_prepareDetailData(intval($this->_stadiumid));
			$this->display('stadium/detail');
		}
	}
	
	
	
	private function _dealPicture(){
		
    	$this->load->library('Attachment_service');
    	$this->attachment_service->setUserInfo($this->_profile['basic']);
    	
    	$fileUpload = array();
    	$deleteImage = array();
    	
    	for($i = 0; $i <= 5; $i++){
        	$otherFile = $this->attachment_service->addImageAttachment('img'.$i);
        	
        	if($otherFile){
        		$previewFile = $this->attachment_service->resize($otherFile,array('small'));
        		
        		//对已上传的图片 在提交校验其他数据错误的情况下，记住已传图片
        		$fileUpload[$i] = array(
        			'aid' => $otherFile['id'],
        			'url' => $otherFile['file_url'],
        			'preview' => $previewFile['img_small']
        		);
        	}else{
        		
        		//js 前台删除图片了， 再次提交后再清楚。
        		$otherImgUrl = $this->input->post('img'.$i.'_url');
        		
        		if(!empty($otherImgUrl)){
        			$fileUpload[$i] = array(
						'aid' => $this->input->post('img'.$i.'_aid'),
        				'url' => $otherImgUrl,
        				'preview' => $this->input->post('img'.$i.'_preview')
        			);
        		}
        	}
        	
        	$delId = $this->input->post('img'.$i.'_aid');
        	$delUrl = $this->input->post('img'.$i.'_url');
        	
        	//删除了图片
        	if($delId && empty($delUrl)){
        		$deleteImage[] = $delId;
        	}
        }
        
        if($deleteImage){
        	$this->attachment_service->deleteFiles($deleteImage);
        }
		
		//现在的照片
		return array('currentImg' => $fileUpload);
	}
	
	/**
	 * 存储数据的时候裁切图片
	 */
	private function _cutImage($images){
		foreach($images as $imgK => $img){
			$file = $this->attachment_service->resize(array('file_url' => $img['url']),array('large','big','middle'));
			$images[$imgK]['avatar_large'] = $file['img_large'];
			$images[$imgK]['avatar_big'] = $file['img_big'];
			$images[$imgK]['avatar_middle'] = $file['img_middle'];
			
			$this->attachment_service->deleteFiles($img['aid'],array('small'));
		}
		
		return $images;
	}
	
	
	/**
	 * 添加场馆
	 */
	public function add(){
        
        if(!$this->isLogin()){
        	
        	$this->assign('returnUrl',site_url('stadium/add'));
        	$this->display('member/login');
        	
        }else{
        	
        	$this->setLeftNavLink('<a id="leftBarLink" class="bar_button" href="'.site_url('stadium').'" title="返回体育场馆">返回</a>');
		
        	$this->assign('maxOtherFile',range(0,5));
        	$stadiumOwnerList = $this->stadium_service->getStadiumMeta(array('where' => array('gname' => '权属')));
			$this->assign('stadiumOwnerList',$stadiumOwnerList);
        	$this->assign('openType',$this->stadium_service->getOpenType());
        	
			$this->seo('报告体育场馆');
	        
	        if($this->isPostRequest()){
	            //$this->form_validation->set_error_delimiters('<div class="error">','</div>');
	            for($i = 0 ; $i < 1; $i++){
	            	$this->_commonRules();
	            	
			        $dealData = $this->_dealPicture();
			        
			        $fileUpload = $dealData['currentImg'];
			        
			        $this->assign('fileUpload',$fileUpload);
			        
		            if($this->form_validation->run() == false){
		            	if(empty($fileUpload[0]['aid'])){
		            		$this->assign('img_error0','请上传场馆封面照片,JPG格式');
		            	}
		            	
		            	break;
		            }else{
		            	//必须传一张封面
		            	if(empty($fileUpload[0]['aid'])){
		            		$this->assign('img_error0','请上传场馆封面照片,JPG格式');
		            		break;
		            	}
		            }
		            
	                $id = $this->stadium_service->addStadium($_POST,$this->_cutImage($fileUpload),$this->_profile['basic']);
	                
	                if($id > 0){
	                	redirect('stadium/detail/'.$id);
	                }else{
	                	$this->assign('feedback','<div class="warning">体育场馆创建失败，请刷新页面重新尝试。</div>');
	                }
	            }
	            
	            $this->display('stadium/add');
	        }else{
	        	$this->display('stadium/add');
	        }
        }
        
	}
    
    private function _commonRules(){
        
        $this->form_validation->set_rules('title','场馆名称','required|min_length[1]|max_length[20]',
            array(
                'required' => '请输入场馆名称'
            )
        );
        
        $this->form_validation->set_rules('address','场馆地址',array('required'),
            array(
                'required' => '请标记场馆位置'
            )
        );
        
        
        if(!empty($_POST['contact'])){
        	$this->form_validation->set_rules('contact','联系人','max_length[10]');
        }
        
        if(!empty($_POST['mobile'])){
        	$this->form_validation->set_rules('mobile','手机号码','valid_mobile');
        }
        
        if(!empty($_POST['tel'])){
        	$this->form_validation->set_rules('tel','座机号码','valid_telephone');
        }
        
    }
    
    
    
    
}
