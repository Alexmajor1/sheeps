$(document).ready(function(){
	function addSheep(paddock, name){
		$.ajax('/api/v1/store',{
			method: 'POST',
			data: {
				_token: $('meta[name="csrf-token"]').attr('content'),
				paddock_num: paddock,
				name: name
			}
		});
	}
	
	function moveSheep(paddock, name){
		$.ajax('/api/v1/update',{
			method: 'POST',
			data: {
				_token: $('meta[name="csrf-token"]').attr('content'),
				paddock_num: paddock,
				name: name
			}
		});
	}
	
	function removeSheep(name){
		$.ajax('/api/v1/destroy',{
			method: 'POST',
			data: {
				_token: $('meta[name="csrf-token"]').attr('content'),
				name: name
			}
		});
	}
	
	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays*24*60*60*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=Strict";
	}
	
	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
	
	function getRandomInt(max) {
		return Math.floor(Math.random() * Math.floor(max));
	}
	
	if(getCookie('day') == ''){
		var day = 0;
	}else{
		var day = getCookie('day');
	}
	
	setInterval(function(){
		sheeps = $('option');
		n = sheeps.length;
		
		if((day>0)&&(day%10)==0){
			i = getRandomInt(n-1);
			removeSheep($(sheeps[i])[0].innerText);
			$(sheeps[i]).parent().remove('#'+$(sheeps[i])[0].id);
		}
		
		name = $(sheeps[n-1])[0].innerText;
		arr = name.split('ка');
		num = +(arr[1]);
		
		for(i=0;i<4;i++){
			count = $($('select')[i]).children('option').length;
			if(count>1){
				++num;
				$($('select')[i]).append('<option id="s'+num+'">овечка '+num+'</option>');
				addSheep(i+1, 'овечка '+num);
			}
		}
		
		min = $($('select')[0]);
		minId = 0;
		max = $($('select')[3]);
		
		for(i=0;i<4;i++){
			paddock = $($('select')[i]);
			if(paddock[0].length > max[0].length){
				max = paddock;
			}else if(paddock[0].length < min[0].length){
				min = paddock;
				minId = i;
			}
		}
		
		if(min[0].length == 1){
			i = getRandomInt(max.length-1);
			sheep = $(max.children('option')[i]);
			$(min[0]).append(sheep[0].outerHTML);
			max[0].remove('#'+sheep[0].id);
			moveSheep(minId, sheep[0].innerText);
		}
		
		setCookie('day', ++day, 1);
	}, 10000);
});