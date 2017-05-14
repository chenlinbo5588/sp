<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 淘宝商品解析起
 */
class Taobao_Item_parse extends MY_Controller {


	public function __construct(){
		parent::__construct();
		
		if(!is_cli()){
			exit();
		}
	}
	
	public function index(){
		
		$fileInfo = file_get_contents('item5.html');
		$fileInfo = iconv('GBK','UTF-8',$fileInfo);
		
		$taobaoItemCnt = preg_match('/<link\s*?href=\"\/\/item\.taobao\.com\/item\.html\?id=(\d+)"\s*?rel=\"canonical\">/is',$fileInfo,$itemMatch);
		
		
		$commonData = array();
		
		
		for($i = 0; $i < 1; $i++){
			
			if(0 == $taobaoItemCnt){
				break;
			}
			
			$commonData['item_id'] = $itemMatch[1];
			
			
			//file_put_contents('match.txt',print_r($itemMatch[1],true));
			$shopCnt = preg_match('/<meta name=\"microscope-data\" content=\".*?shopId=(\d+);userid=(\d+);\">/is',$fileInfo,$shopMatch);
			
			//file_put_contents('match.txt',print_r($shopMatch[1],true),FILE_APPEND);
			//file_put_contents('match.txt',print_r($shopMatch[2],true),FILE_APPEND);
			
			$commonData['shop_id'] = $shopMatch[1];
			$commonData['user_id'] = $shopMatch[2];
			
			
			$titleCnt = preg_match('/<title>(.*?)<\/title>/is',$fileInfo,$titleMatch);
			$shopNameCnt = preg_match('/<div class=\"tb-shop-name\">(.*?)<\/div>/is',$fileInfo,$shopNameMatch);
			
			//print_r($shopNameMatch);
			
			$commonData['title'] = str_replace('-淘宝网/','',$titleMatch[1]);
			$commonData['shop_name'] = trim(strip_tags($shopNameMatch[1]));
			
			
			//file_put_contents('match.txt',print_r($titleMatch[1],true),FILE_APPEND);
			
			$sizeCnt = preg_match('/<ul.*?data-property=\"鞋码\".*?>(.*?)<\/ul>/is',$fileInfo,$sizeMatch);
			
			
			if($sizeCnt){
				$sizeCnt2 = preg_match_all('/<li data-value="(\d+:\d+)".*?<span>\s*?(\d+\.?\d?)/is',$sizeMatch[1],$sizeMatch2);
				//print_r($sizeMatch2);
			}
			
			
			//print_r($colorMatch);
			//file_put_contents('match.txt',print_r($sizeMatch[1],true),FILE_APPEND);
			
			$colorCnt = preg_match('/<ul.*?data-property=\"颜色分类\".*?>(.*?)<\/ul>/is',$fileInfo,$colorMatch);
		
			//print_r($colorMatch);
			//file_put_contents('match.txt',print_r($colorMatch[1],true),FILE_APPEND);
			
			if($colorCnt){
				$colorCnt2 = preg_match_all('/<li data-value="(\d+:\d+)".*?<a(.*?)>.*?<span>(.*?)</is',$colorMatch[1],$colorMatch2);
				print_r($colorMatch2);
			}
			
			
			/*
			//尺码+款式     20549:28393;1627207:28321
			
			$skuCnt = preg_match('/skuMap\s*?\:(.*?)\,propertyMemoMap\:/is',$fileInfo,$skuMatch);
			//file_put_contents('match.txt',print_r($skuMatch[1],true),FILE_APPEND);
			
			$skuJson = json_decode($skuMatch[1],true);
			file_put_contents('match.txt',print_r($skuJson,true),FILE_APPEND);
			
			
			echo count($skuJson);
			$minPrice = 0;
			*/
			
			$dynStockClient = new Http_client();
			
			$resp = $dynStockClient->request(array(
				'custom_header' => array('referer:https://item.taobao.com/item.htm?id='.$commonData['item_id']),
				'method' => 'GET',
				'url' => "https://detailskip.taobao.com/service/getData/1/p1/item/detail/sib.htm?itemId={$commonData['item_id']}&sellerId={$commonData['user_id']}&modules=dynStock,price&callback=onSibRequestSuccess",
			));
			
			if(strpos($resp,'onSibRequestSuccess') !== false){
				$jsonString = substr($resp,strpos($resp,'(') + 1,-2);
				$stockJson = json_decode($jsonString,true);
				//print_r($stockJson);
				
				if($stockJson['code']['code'] != 0){
					break;
				}
			}else{
				break;
			}
			
			
			$sizeList = $stockJson['data']['dynStock']['sku'];
			
			//item.taobao.com/item.html?id=527470899252
			
			$insertData = array();
			
			
			foreach($colorMatch2[1] as $colorIndex => $color){
				//自定义SKU
				$asize = array(
					'custom_sku' => $colorMatch2[3][$colorIndex],
					'img_s' => '',
					'img_b' => '',
				);
				
				$imgCnt = preg_match('/background:url\((.*?)\)/is',$colorMatch2[2][$colorIndex],$imgMatch);
				if($imgCnt){
					$asize['img_s'] = 'https:'.$imgMatch[1];
					$asize['img_b']	 = str_replace('30x30.jpg','400x400.jpg',$asize['img_s']);
				}
				
				//每一种款式 ，可用的尺码不一样
				$avaliableSize = array();
				$avaliableStock = array();
				
				//HTML页面上罗列的尺码
				foreach($sizeMatch2[1] as $sizeIndex => $size){
					$key = ";{$size};{$color};";
					
					if($sizeList[$key]){
						$avaliableSize[] = $sizeMatch2[2][$sizeIndex];
						$avaliableStock[] = $sizeList[$key]['sellableQuantity'];
					}
				}
				
				$asize['size'] = implode('/',$avaliableSize);
				$asize['stock'] = implode('/',$avaliableStock);
				$asize = array_merge($commonData,$asize);
				
				$insertData[] = $asize;
				
			}
			
			
			print_r($insertData);
				
		}
		
	}
	
}
