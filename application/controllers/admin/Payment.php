<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Payment extends Ydzj_Admin_Controller {
	
	
	
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Payment_Model');
	}
	
	public function system(){
		
		$list = $this->Payment_Model->getList();
		
		
		$this->assign('paymentList',$list);
		$this->display();
	}
	
	
	public function edit(){
		
		$payment_code = $this->input->get_post('payment_code');
		
		$feedback = '';
		
		if($this->isPostRequest()){
			$this->form_validation->set_rules('payment_code','支付方式代码','required|in_list[offline,alipay,tenpay,chinabank,predeposit]');
			$this->form_validation->set_rules('payment_state','启用状态','required|in_list[0,1]');
			
			
			$data = array(
				'payment_state' => $this->input->post('payment_state')
			);
					
			switch($payment_code){
				case 'offline':
				
					break;
				case 'alipay':
					$this->form_validation->set_rules('alipay_account','支付宝账号','required');
					$this->form_validation->set_rules('alipay_key','交易安全校验码','required');
					$this->form_validation->set_rules('alipay_partner','合作者身份','required');
					
					$data['payment_config'] = array(
						'alipay_account' => $this->input->post('alipay_account'),
						'alipay_key' => $this->input->post('alipay_key'),
						'alipay_partner' => $this->input->post('alipay_partner')
					);
					
					break;
				case 'tenpay':
					$this->form_validation->set_rules('tenpay_account','财付通商户号','required');
					$this->form_validation->set_rules('tenpay_key','财付通密钥','required');
					
					$data['payment_config'] = array(
						'tenpay_account' => $this->input->post('tenpay_account'),
						'tenpay_key' => $this->input->post('tenpay_key'),
					);
					
					break;
				case 'chinabank':
					$this->form_validation->set_rules('chinabank_account','网银在线商户号','required');
					$this->form_validation->set_rules('chinabank_key','网银在线密钥','required');
					
					$data['payment_config'] = array(
						'chinabank_account' => $this->input->post('chinabank_account'),
						'chinabank_key' => $this->input->post('chinabank_key'),
					);
					
					break;
				case 'predeposit':
				
					break;
				default:
					break;
				
			}
			
			
			
			
			for($i = 0; $i < 1; $i++) {
				
				if(!$this->form_validation->run()){
					$feedback = getErrorTip($this->form_validation->error_string());
					break;
				}
				
				$data['payment_config'] = json_encode($data['payment_config']);
				
				$row = $this->Payment_Model->update($data,array('payment_code' => $payment_code));
				
				if($row < 0){
					$feedback = getErrorTip('保存失败');
					break;
				}
				
				$feedback = getSuccessTip('保存成功');
			}
			
		}
		
		
		$info = $this->Payment_Model->getFirstByKey($payment_code,'payment_code');
		
		$info['payment_config'] = json_decode($info['payment_config'],true);
				
		$this->assign('feedback',$feedback);
		$this->assign('info',$info);
		$this->display('payment/'.$info['payment_code']);
	}
	
}
