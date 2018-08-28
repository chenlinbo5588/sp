/**
 * 个人中心 消息通知
 */
;$.myNotify = {
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
	useWebNotify:false,
	init : function( initSetting ){
		if(window.Notification){
			if (Notification.permission == "granted") {
				// empty
	        } else if (Notification.permission != "denied") {
	            Notification.requestPermission(function (permission) {
	            	// empty
	            });
	        }
		}
		
		return this;
	},
	initSWF: function(targetDiv,soundswf,expressswf){
		
		/*
    	var flashvars = {
        };
        var params = {
            wmode: "transparent"
        }; 
        
        this.wrapDiv = targetDiv;
        
        var attributes = {};
        swfobject.embedSWF(soundswf, targetDiv, "1", "1", "9.0.0", expressswf, flashvars, params, attributes);
        this.swfobject = swfobject;
        */
	
        return this;
    },
    
    showToast:function(){
    	$.toast({
            position:'bottom-center',
            text: "<a href=\"" + this.pmUrl + "\" target=\"workspace\">您有新的消息，请点击查看</a>",
            icon: 'info',
            hideAfter:8000,
            bgColor: '#f14e32',
            textColor: 'white',
            stack:1,
            loader:false
        });
    },
    webNotify:function(){
    	if (Notification.permission == "granted") {
            var notification = new Notification("新消息提醒", {
                body: '您有新的消息,请注意查收'
            });
            
            notification.onclick = function() {
            	window.focus();
            };
        }
    },
    updatePm:function(url){
    	var that = this;
    	if(this.pmInterval){
    		clearInterval(this.pmInterval);
    	}
    	
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
    				that.pmInUpdate = false;
    				
    				if(json.data.newPm > 0){
    					that.showToast();
    					//that.playSound(1);
    					
    					if(window.Notification){
    						if (Notification.permission == "granted") {
    				            console.log("New Message");
    							//popNotice();
    				        } else if (Notification.permission != "denied") {
    				            Notification.requestPermission(function (permission) {
    				              //popNotice();
    				            	console.log(permission);
    				            });
    				        }
    						
    					}else{
    						that.titleChange();
    					}
    				}
    			},
    			errror: function(XMLHttpRequest, textStatus, thrownError){
    				that.pmInUpdate = false;
    			}
    		});
    	},30*1000);
    },
    setPmUrl: function(url){
    	this.pmUrl = url;
    },
    setSound: function(mp3Url){
    	this.soudMp3 = mp3Url;
    },
    
    titleChange: function(){
    	this.orignalTitle = document.title;
    	
    	document.title = this.titleTip;
    	
    	var that = this;
    	if(this.titleInterval){
    		clearInterval(this.titleInterval);
    	}
    	
    	that.titleChangeTimes = 0;
    	this.titleInterval = setInterval(function(){
    		that.titleChangeTimes++;
    		
    		if(that.titleChangeTimes > 100){
    			that.titleChangeTimes = 0;
    			
    			document.title = that.orignalTitle;
    			
    			if(that.titleInterval){
    	    		clearInterval(that.titleInterval);
    	    	}
    			return ;
    		}
    		
    		if (/新/.test(document.title) == false) {
                document.title = '【你有新消息】';    
            } else {
                document.title = '【　　　　　】';
            }
    		
    		/*
    		var s = document.title;
    		if(s == that.titleTip){
    			document.title = that.orignalTitle;
    		}else{
    			document.title = that.titleTip;
    		}
    		*/
    		
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