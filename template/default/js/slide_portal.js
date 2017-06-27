var $$$=function(id){return"string"==typeof id?document.getElementById(id):id;};var Class={create:function(){return function(){this.initialize.apply(this,arguments);}}}
Object.extend=function(destination,source){for(var property in source){destination[property]=source[property];}
return destination;}
var TransformView=Class.create();TransformView.prototype={initialize:function(container,slider,parameter,count,options){if(parameter<=0||count<=0)return;var oContainer=$$$(container),oSlider=$$$(slider),oThis=this;this.Index=0;this._timer=null;this._slider=oSlider;this._parameter=parameter;this._count=count||0;this._target=0;this.SetOptions(options);this.Up=!!this.options.Up;this.Step=Math.abs(this.options.Step);this.Time=Math.abs(this.options.Time);this.Auto=!!this.options.Auto;this.Pause=Math.abs(this.options.Pause);this.onStart=this.options.onStart;this.onFinish=this.options.onFinish;oContainer.style.overflow="hidden";oContainer.style.position="relative";oSlider.style.position="absolute";oSlider.style.top=oSlider.style.left=0;},SetOptions:function(options){this.options={Up:true,Step:3,Time:20,Auto:true,Pause:2000,onStart:function(){},onFinish:function(){}};Object.extend(this.options,options||{});},Start:function(){if(this.Index<0){this.Index=this._count-1;}else if(this.Index>=this._count){this.Index=0;}
this._target=-1*this._parameter*this.Index;this.onStart();this.Move();},Move:function(){clearTimeout(this._timer);var oThis=this,style=this.Up?"top":"left",iNow=parseInt(this._slider.style[style])||0,iStep=this.GetStep(this._target,iNow);if(iStep!=0){this._slider.style[style]=(iNow+iStep)+"px";this._timer=setTimeout(function(){oThis.Move();},this.Time);}else{this._slider.style[style]=this._target+"px";this.onFinish();if(this.Auto){this._timer=setTimeout(function(){oThis.Index++;oThis.Start();},this.Pause);}}},GetStep:function(iTarget,iNow){var iStep=(iTarget-iNow)/this.Step;if(iStep==0)return 0;if(Math.abs(iStep)<1)return(iStep>0?1:-1);return iStep;},Stop:function(iTarget,iNow){clearTimeout(this._timer);this._slider.style[this.Up?"top":"left"]=this._target+"px";}};window.onload=function(){function Each(list,fun){for(var i=0,len=list.length;i<len;i++){fun(list[i],i);}};var objs=$$$("idNum").getElementsByTagName("li");var tv=new TransformView("idTransformView","idSlider",194,3,{onStart:function(){Each(objs,function(o,i){o.className=tv.Index==i?"on":"";})}});tv.Start();Each(objs,function(o,i){o.onmouseover=function(){o.className="on";tv.Auto=false;tv.Index=i;tv.Start();}
o.onmouseout=function(){o.className="";tv.Auto=true;tv.Start();}})}