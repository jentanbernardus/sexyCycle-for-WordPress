/**
 * jQuery sexyCycle v0.3 (With alterations)
 *
 * Terms of Use - jQuery sexyCycle
 * under the MIT (http://www.opensource.org/licenses/mit-license.php) License.
 *
 * Copyright 2010 suprb.com All rights reserved.
 * (http://suprb.com/apps/sexyCycle/)
 */

(function(a){a.fn.sexyCycle=function(j){var m={easing:"easeOutExpo",speed:400,next:null,prev:null,start:0,interval:false,cycle:true,imgclick:true,counter:null};var j=a.extend(m,j);var l=this;var p=true;var g=a(".sexyCycle-wrap",this);var k=j.start;var y=a(l).width();var B=a(".sexyCycle-content img:eq(0)",g).attr("src");a("body").append('<span class="sexycycleimgtempload"></span>');a(".sexycycleimgtempload").hide();var f=0,u=0,z=0;var v=a(".sexyCycle-content img:eq(0)",g).height();a(g).height(v);a(".sexycycleimgtempload").remove();var x=a(".sexyCycle-content",g).children().size();var A=a(".sexyCycle-content img:eq("+j.start+")",g).width();var i=a(".sexyCycle-content img:eq(0)",g);var d=a(".sexyCycle-content img:eq("+(x-1)+")",g);var c=a(".sexyCycle-content img:eq("+(x-1)+")",g).attr("src");var f=a(".sexyCycle-content img:eq("+(x-1)+")",g).height();var s=a(".sexyCycle-content img:eq("+(x-1)+")",g).width();var h=a(".sexyCycle-content img:eq(0)",g).attr("src");var o=a(".sexyCycle-content img:eq(0)",g).height();var r=a(".sexyCycle-content img:eq(0)",g).width();for(_lc=0;_lc<j.start;_lc++){u+=a(".sexyCycle-content img:eq("+_lc+")",g).width()}if(a(j.counter).length>0){a(j.counter).html("<span style='counter-text'>"+(k+1)+" of "+x+"</span>")}a('<span class="sexyCycleTempf" style="background: url(\''+c+"'); float: left; width: "+s+"px; height: "+f+'px; display: block"></span>').insertBefore(a(".sexyCycle-content li:eq(0)",g));a(".sexyCycleTempf",g).css("display","none");a('<span class="sexyCycleTempe" style="background: url(\''+h+"'); float: left; width: "+r+"px; height: "+o+'px; display: block"></span>').insertAfter(a(".sexyCycle-content li:eq("+(x-1)+")",g));a(".sexyCycleTempe",g).css("display","none");var t=A;a("li",g).css("float","left");a(l).css("width",t+"px");a(".sexyCycle-content",g).animate({left:"-="+u+"px"},{duration:0});a(l).css("height",(a(g).height()+a(".controllers",l).height()+10)+"px");a(j.next).click(function(){b("+")});a(j.prev).click(function(){b("-")});if(j.imgclick){a(g).click(function(){b("+")})}if(j.interval!=false){var w=j.stop;n(w)}a(j.stop).click(function(){q(w)});function n(){b("+");w=setTimeout(n,j.interval)}function q(e){clearInterval(w)}function b(e){if(p==true){p=false;if(e=="-"){slideto="+=";z=k-1;A=a(".sexyCycle-content img:eq("+(z)+")",g).width()}else{slideto="-=";z=k+1;A=a(".sexyCycle-content img:eq("+(z-1)+")",g).width()}if(z-1<x-1&&z-1>=-1){t=a(".sexyCycle-content img:eq("+z+")",g).width();_h=a(".sexyCycle-content img:eq("+z+")",g).height()+10;a(".sexyCycle-content",g).animate({left:slideto+A+"px"},{duration:j.speed,easing:j.easing});a(l).animate({width:t+"px",height:_h+"px"},{duration:j.speed,easing:j.easing,complete:function(){p=true}});k=z}else{if(j.cycle==true){if(e=="+"){k=0;z=0;a(".sexyCycleTempf",g).css("display","block");a(".sexyCycle-content",g).css("left","0px");a(".sexyCycle-content",g).animate({left:slideto+A+"px"},{duration:j.speed,easing:j.easing})}else{k=x-1;z=x-1;a(".sexyCycleTempe",g).css("display","block");a(".sexyCycle-content",g).css("left","-"+a(".sexyCycleTempe",g).position().left+"px");a(".sexyCycle-content",g).animate({left:slideto+s+"px"},{duration:j.speed,easing:j.easing})}t=a(".sexyCycle-content img:eq("+k+")",g).width();_h=a(".sexyCycle-content img:eq("+k+")",g).height()+10;a(l).animate({width:t+"px",height:_h+"px"},{duration:j.speed,easing:j.easing,complete:function(){p=true}})}else{p=true}}}if(a(j.counter).length>0){a(j.counter).html("<span style='counter-text'>"+(k+1)+" of "+x+"</span>")}}}})(jQuery);