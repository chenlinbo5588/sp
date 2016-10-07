/**
 * 个人中心 消息通知
 */
;$.fn.myNotify = {
	wrapDiv: "sound",
	swfobject: null,
	soudMp3: '',
	pmUrl:'',
	titleTip : "【您有新想消息】，请注意查收",
	orignalTitle: '',
	titleChangeTimes : 0,
	titleInterval: null,
	pmInterval:null,
	pmInUpdate: false,
	initSWF: function(targetDiv,soundswf,expressswf){
    	var flashvars = {
        };
        var params = {
            wmode: "transparent"
        }; 
        
        this.wrapDiv = targetDiv;
        
        var attributes = {};
        swfobject.embedSWF(soundswf, targetDiv, "1", "1", "9.0.0", expressswf, flashvars, params, attributes);
        this.swfobject = swfobject;
        
        return this;
    },
    showToast:function(){
    	$.toast({
            position:'bottom-center',
            text: "<a href=\"" + this.pmUrl + "\">您有新的消息，请点击查看</a>",
            icon: 'info',
            hideAfter:30000,
            bgColor: '#324DFF',
            textColor: 'white',
            stack:1,
            loader:false
        });
    },
    updatePm(url){
    	var that = this;
    	if(this.pmInterval){
    		clearInterval(this.pmInterval);
    	}
    	
    	// 180  秒自动更新一下
    	this.pmInterval = setInterval(function(){
    		//console.log(that.pmInUpdate);
    		if(that.pmInUpdate){
        		return ;
        	}
        	
    		that.pmInUpdate = true;
    		//console.log(that.pmInUpdate);
    		
    		$.ajax({
    			type:'get',
    			url:url,
    			timeout: 5000,
    			data:{ t : Math.random() },
    			dataType: 'json',
    			success:function(json){
    				if(json.data.newpm > 0){
    					that.showToast();
    					that.playSound(1);
    					that.titleChange(50);
    				}
    				
    				that.pmInUpdate = false;
    			},
    			errror: function(XMLHttpRequest, textStatus, thrownError){
    				that.pmInUpdate = false;
    			}
    		});
    	},60*1000);
    },
    setPmUrl: function(url){
    	this.pmUrl = url;
    },
    setSound: function(mp3Url){
    	this.soudMp3 = mp3Url;
    },
    
    titleChange: function(){
    	this.orignalTitle = $("title").html();
    	$("title").html(this.titleTip);
    	
    	var that = this;
    	if(this.titleInterval){
    		clearInterval(this.titleInterval);
    	}
    	
    	that.titleChangeTimes = 0;
    	this.titleInterval = setInterval(function(){
    		that.titleChangeTimes++;
    		
    		if(that.titleChangeTimes > 100){
    			that.titleChangeTimes = 0;
    			$("title").html(that.orignalTitle);
    			
    			if(that.titleInterval){
    	    		clearInterval(that.titleInterval);
    	    	}
    			return ;
    		}
    		
    		var s = $("title").html();
    		if(s == that.titleTip){
    			$("title").html(that.orignalTitle);
    		}else{
    			$("title").html(that.titleTip);
    		}
    		
    	},500);
    },
    
    playSound: function(playTimes) {
        var sound = swfobject.getObjectById(this.wrapDiv);
        if (sound) {
            sound.SetVariable("f", this.soudMp3);
            sound.GotoFrame(1);
            
            if(typeof(playTimes) == 'undefined'){
            	playTimes = 1;
            }
            
            var replayFn = function(soundObject ,seconds){
        		setTimeout(function(){
        			soundObject.GotoFrame(1);
        		},seconds * 2000)
        	}
            
            for(var i = playTimes - 1; i > 0 ; i--){
            	replayFn(sound,i);
            }
        }
    }
};