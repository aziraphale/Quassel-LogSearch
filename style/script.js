//
//      Quassel Backlog Search
//      developed 2009-2012 by m4yer <m4yer@minad.de> under a Creative Commons Licence by-nc-sa 3.0
//
//
//  quasselbacklogsearchjs - dunno how but it works :P
//  here is all the magic ...
//
//

     Ajax.Responders.register({
          onCreate: function() {
        document.getElementById('load').style.display = 'block';
          },
          onComplete: function() {
        document.getElementById('load').style.display = 'none';
          }
     });

     function stopError(){
        return true;} 

     onerror = stopError;

	function roundthis(target){
		target.value = Math.round(target.value / 5) * 5;
		return true;
		}

     function leer(check){
     }

    function readCookie(name) {
    	var nameEQ = name + "=";
    	var ca = document.cookie.split(';');
    	for(var i=0;i < ca.length;i++) {
    		var c = ca[i];
    		while (c.charAt(0)==' ') c = c.substring(1,c.length);
    		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);}
    	return null;}


     function Request(divid,page)     {
             new Ajax.Updater(divid, page, {asynchronous:true, evalScripts:true});}


     function such()     {
        var ary = new Array();

        for(i = 0; i < document.getElementById('buffer').options.length; i++) {
        if(document.getElementById('buffer').options[i].selected) {
            ary.push(document.getElementById('buffer').options[i].value);
        }     }

             new Ajax.Updater('scontent', 'suche.php?search=1&string=' + encodeURIComponent(document.getElementById('input').value) + '&buffername=' + ary + '&number=' + document.getElementById('number').value + '&time_end=' + encodeURIComponent(document.getElementById('time_end').value) + '&time_start=' + encodeURIComponent(document.getElementById('time_start').value) + '&regexid=' + document.getElementById('regexid').checked + '&types=' + document.getElementById('types').checked + '&sorting=' + document.getElementById('sorting').checked, {asynchronous:true, evalScripts:true});
             document.cookie = 'saves=' + document.getElementById('regexid').checked +':'+ document.getElementById('types').checked +':'+ document.getElementById('sorting').checked;
             }


     function such_more(sorting)     {
        document.getElementById('morespan').style.visibility = 'hidden';
        var ary = new Array();

        for(i = 0; i < document.getElementById('buffer').options.length; i++) {
        if(document.getElementById('buffer').options[i].selected) {
            ary.push(document.getElementById('buffer').options[i].value);
        }     }
        
        if(sorting == 0){
             new Ajax.Updater('innersearch', 'suche.php?search=1&string=' + encodeURIComponent(document.getElementById('input').value) + '&searchid=' + encodeURIComponent(document.getElementById('searchid').innerHTML) + '&buffername=' + ary + '&number=' + document.getElementById('number').value + '&time_end=' + encodeURIComponent(document.getElementById('time_end').value) + '&time_start=' + encodeURIComponent(document.getElementById('time_start').value) + '&regexid=' + document.getElementById('regexid').checked + '&types=' + document.getElementById('types').checked + '&sorting=' + document.getElementById('sorting').checked, {asynchronous:true, evalScripts:true, insertion: Insertion.Bottom, onComplete: function(){document.getElementById('morespan').style.visibility = 'visible';}});
        }else{
             new Ajax.Updater('innersearch', 'suche.php?search=1&string=' + encodeURIComponent(document.getElementById('input').value) + '&searchid=' + encodeURIComponent(document.getElementById('searchid').innerHTML) + '&buffername=' + ary + '&number=' + document.getElementById('number').value + '&time_end=' + encodeURIComponent(document.getElementById('time_end').value) + '&time_start=' + encodeURIComponent(document.getElementById('time_start').value) + '&regexid=' + document.getElementById('regexid').checked + '&types=' + document.getElementById('types').checked + '&sorting=' + document.getElementById('sorting').checked, {asynchronous:true, evalScripts:true, insertion: Insertion.Top, onComplete: function(){document.getElementById('morespan').style.visibility = 'visible';}});      
        }
        
        
        document.cookie = 'saves=' + document.getElementById('regexid').checked +':'+ document.getElementById('types').checked +':'+ document.getElementById('sorting').checked;
        
             }
             
             
function rellig(){

        var ary = new Array();
        for(i = 0; i < document.getElementById('buffer').options.length; i++) {
        if(document.getElementById('buffer').options[i].selected) {
            ary.push(document.getElementById('buffer').options[i].value);
        }     }

             new Ajax.Updater('scontent', 'suche.php?search=1&string=' + encodeURIComponent(document.getElementById('input').value) + '&buffername=' + ary + '&number=1&time_end=' + encodeURIComponent(document.getElementById('time_end').value) + '&time_start=' + encodeURIComponent(document.getElementById('time_start').value) + '&regexid=' + document.getElementById('regexid').checked + '&types=' + document.getElementById('types').checked + '&sorting=' + document.getElementById('sorting').checked, {asynchronous:true, evalScripts:true, onComplete: function(){}});

for(j = 1; j < document.getElementById('number').value; j++){

        
        if(document.getElementById('sorting').checked == 1){
             new Ajax.Updater('innersearch', 'suche.php?search=1&string=' + encodeURIComponent(document.getElementById('input').value) + '&searchid=' + j + '&buffername=' + ary + '&number=' + '1' + '&time_end=' + encodeURIComponent(document.getElementById('time_end').value) + '&time_start=' + encodeURIComponent(document.getElementById('time_start').value) + '&regexid=' + document.getElementById('regexid').checked + '&types=' + document.getElementById('types').checked + '&sorting=' + document.getElementById('sorting').checked, {asynchronous:true, evalScripts:true, insertion: Insertion.Top});
        }else{
             new Ajax.Updater('innersearch', 'suche.php?search=1&string=' + encodeURIComponent(document.getElementById('input').value) + '&searchid=' + j + '&buffername=' + ary + '&number=' + '1' + '&time_end=' + encodeURIComponent(document.getElementById('time_end').value) + '&time_start=' + encodeURIComponent(document.getElementById('time_start').value) + '&regexid=' + document.getElementById('regexid').checked + '&types=' + document.getElementById('types').checked + '&sorting=' + document.getElementById('sorting').checked, {asynchronous:true, evalScripts:true, insertion: Insertion.Bottom});      
        }  
  
}
document.cookie = 'saves=' + document.getElementById('regexid').checked +':'+ document.getElementById('types').checked +':'+ document.getElementById('sorting').checked;
 
}


function rellig_more(sorting){
  document.getElementById('morespan').style.visibility = 'hidden';
       var ary = new Array();
        for(i = 0; i < document.getElementById('buffer').options.length; i++) {
        if(document.getElementById('buffer').options[i].selected) {
            ary.push(document.getElementById('buffer').options[i].value);
        }     }

for(j = 0; j < document.getElementById('number').value; j++){

        var k = j+ parseInt(document.getElementById('searchid').innerHTML);
        if(sorting == 1){
             new Ajax.Updater('innersearch', 'suche.php?search=1&string=' + encodeURIComponent(document.getElementById('input').value) + '&searchid=' + k + '&buffername=' + ary + '&number=' + '1' + '&time_end=' + encodeURIComponent(document.getElementById('time_end').value) + '&time_start=' + encodeURIComponent(document.getElementById('time_start').value) + '&regexid=' + document.getElementById('regexid').checked + '&types=' + document.getElementById('types').checked + '&sorting=' + document.getElementById('sorting').checked, {asynchronous:true, evalScripts:true, insertion: Insertion.Top});
        }else{
             new Ajax.Updater('innersearch', 'suche.php?search=1&string=' + encodeURIComponent(document.getElementById('input').value) + '&searchid=' + k + '&buffername=' + ary + '&number=' + '1' + '&time_end=' + encodeURIComponent(document.getElementById('time_end').value) + '&time_start=' + encodeURIComponent(document.getElementById('time_start').value) + '&regexid=' + document.getElementById('regexid').checked + '&types=' + document.getElementById('types').checked + '&sorting=' + document.getElementById('sorting').checked, {asynchronous:true, evalScripts:true, insertion: Insertion.Bottom});      
        }  
  
}
document.getElementById('morespan').style.visibility = 'visible';
document.cookie = 'saves=' + document.getElementById('regexid').checked +':'+ document.getElementById('types').checked +':'+ document.getElementById('sorting').checked;
 
}  


     function moreinfo(divid,bufferid,types,sorting)     {
             document.getElementById('d'+divid).style.display = 'none';
             document.getElementById('m'+divid).style.display = 'block';
             document.getElementById('m'+divid).style.border = '1px solid #3399cc';
             new Ajax.Updater('m'+divid, 'moreinfo.php?messageid=' +divid+ '&bufferid=' +bufferid+ '&types=' +types+'&sorting='+sorting , {asynchronous:true, evalScripts:true});    }

     function moreup(msgid,bufferid,state,types,sorting){
         new Ajax.Updater('wantmore'+msgid, 'moremore.php?messageid='+document.getElementById(state+msgid).innerHTML+'&bufferid='+bufferid+'&state='+state+'&base='+msgid+ '&types=' +types+'&sorting='+sorting, {asynchronous:true, evalScripts:true,insertion: Insertion.Top});        
        }

     function moredown(msgid,bufferid,state,types,sorting){
         new Ajax.Updater('wantmore'+msgid, 'moremore.php?messageid='+document.getElementById(state+msgid).innerHTML+'&bufferid='+bufferid+'&state='+state+'&base='+msgid+ '&types=' +types+'&sorting='+sorting, {asynchronous:true, evalScripts:true,insertion: Insertion.Bottom});        
        }

     function hide_a_search(){
            document.getElementById('buffer').multiple = false;
            document.getElementById('buffer').size=1;
            document.getElementById('buffer').style.marginBottom= '0px';
            document.getElementById('advanced').style.display='none';
            document.getElementById('time_start').value = 'Starttime';
            document.getElementById('time_end').value = 'Endtime';
            document.getElementById('regexid').checked = false;
            document.getElementById('types').checked = true;
            document.getElementById('asearch').style.display='inline';
            document.getElementById('time_start').style.border= '1px solid black';
            document.getElementById('time_end').style.border= '1px solid black';
        }


     function show_a_search(){
            document.getElementById('asearch').style.display='none';
            document.getElementById('advanced').style.display='inline';
        }


     function multiplejs(){
            if($('buffer').multiple == true){
                $('buffer').style.marginBottom= '0px';
                $('buffer').size=1;
                $('buffer').multiple=false;
                $('multipleide').checked=false;
            }else{
                $('buffer').style.marginBottom= '-43px';
                $('buffer').size=5;
                $('buffer').multiple=true;
		$('multipleide').checked=true;
        }}

       
     function sorting(){
            if(document.getElementById('sorting').checked == true){
                document.getElementById('sorting').checked=false;
                such();
                document.getElementById('sortlink').src="style/view-sort-ascending.png";
            }else{
                document.getElementById('sorting').checked=true;
                such();
                document.getElementById('sortlink').src="style/view-sort-descending.png";
        }}

      function close_more(messageid){
        document.getElementById('m'+messageid).style.display = 'none';
        document.getElementById('d'+messageid).style.display = 'block';}


     function loadconf(){
        var saves = readCookie('saves');
        if(saves != 'false:true:false'){
            var saves = saves.split(':');
            
            for (var i = 0; i < saves.length; ++i){
                if(saves[i] == "true"){
                    saves[i] = true;
                     }else{
                        saves[i]= false;
                        }
                }
            document.getElementById('regexid').checked = saves[0];
            document.getElementById('types').checked = saves[1];
            document.getElementById('sorting').checked = saves[2];
            if(saves[2] = true){
                document.getElementById('sortlink').src="style/view-sort-descending.png";
                }
            if(readCookie('saves') != 'false:true:true'){
                show_a_search();
                }
            }
        }

    function validtime_start(){
        var times = document.getElementById('time_start').value;
        new Ajax.Updater('starttime', 'parsetime.php?time='+times, {onComplete: function(){
        if(document.getElementById('starttime').innerHTML==0){
            document.getElementById('time_start').style.border= '2px solid red';
        }else{
            document.getElementById('time_start').style.border= '1px solid black';
        }
        },asynchronous:true, evalScripts:true}); 
        }

    function validtime_end(){
        var times = document.getElementById('time_end').value;
        new Ajax.Updater('endtime', 'parsetime.php?time='+times, {onComplete: function(){
            if(document.getElementById('endtime').innerHTML==0){
                document.getElementById('time_end').style.border= '2px solid red';
            }else{
                document.getElementById('time_end').style.border= '1px solid black';
            }
        },asynchronous:true, evalScripts:true}); 
        }
