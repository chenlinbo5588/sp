<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tk_Company_News {
    
    public function __construct(){

    }
    
    /**
     *
     * @param type $message 
     */
    public function response($message){
        //print_r($message);
        $now = time();
        
        
        $respXML = <<< EOF
<xml>
<ToUserName><![CDATA[$message[FromUserName]]]></ToUserName>
<FromUserName><![CDATA[$message[ToUserName]]]></FromUserName>
<CreateTime>$now</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>2</ArticleCount>
<Articles>
<item>
<Title><![CDATA[]]>$company_name</Title> 
<Description><![CDATA[企业法人：胡杏涛]]></Description>
<PicUrl><![CDATA[$chzz]]></PicUrl>
<Url><![CDATA[$url]]></Url>
</item>
<item>
<Title><![CDATA[企业法人: 胡杏涛]]></Title>
<Description><![CDATA[慈溪市土地勘测规划设计院有限公司是慈溪市国土资源局所属的原国有企业（慈溪市土地勘测规划设计院）改制后的股份制民营企业。
公司创建于1993年,是以测量为主业，集土地、房屋测量，土地规划设计，地理信息服务，矿业权核查，房地产登记代理于一体，经营多元、结构合理的现代企业。
现有员工70余人，其中中高级职称的技术人员21名，初级职称的技术人员36名，具有全国级、省级土地登记代理人20余名。
公司置有天宝GPS（全球卫星定位系统）、拓普康免棱镜测距1200米的全站仪、惠普5100大型彩色绘图仪、数据交换服务器等设备60多台（套）,
公司与江西省地矿测绘院、武汉大学资源与环境科学学院、丽水职业技术学院、省土地规划设计院等省内外多家单位进行了业务、技术交流与合作关系。
公司通过了ISO9000及GB/T19001-2008标准的质量管理体系认证,持有国家测绘乙级资质、土地规划乙级资质、土地调查登记代理机构注册证书等.
公司为宁波市房产测绘评审专家委员会成员、浙江省测绘与地理信息行业协会副会长单位。]]></Description>
<PicUrl><![CDATA[$qyfr]]></PicUrl>
<Url><![CDATA[$url]]></Url>
</item>
</Articles>
</xml> 
EOF;
        return $respXML;
    }
    
}
