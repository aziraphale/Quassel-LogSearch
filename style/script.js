     function stopError(){
     return true;} 

     function leer(check){
     }

     function Request(divid,page)     {
             document.getElementById(divid).innerHTML = '<br><br><br><br><br><br><br><br><br><br><center><img src="style/loading.gif" style="border:1px solid black;"></center><br>';
             new Ajax.Updater(divid, page, {asynchronous:true, evalScripts:true});}
      
      
     function such()     {
             document.getElementById('scontent').innerHTML = '<center><img src="style/loading.gif"></center>';
             //document.getElementById('scontent').style.background = 'white';
             new Ajax.Updater('scontent', 'suche.php?search=1&string=' + document.getElementById('input').value + '&buffername=' + document.getElementById('buffer').value + '&number=' + document.getElementById('number').value + '&time_end=' + document.getElementById('time_end').value + '&time_start=' + document.getElementById('time_start').value, {asynchronous:true, evalScripts:true});    }
      
      
            
     function moreinfo(divid,bufferid)     {
             document.getElementById('d'+divid).style.display = 'none';
             document.getElementById('m'+divid).style.display = 'block';
             document.getElementById('m'+divid).style.border = '1px solid #3399cc';
             new Ajax.Updater('m'+divid, 'moreinfo.php?messageid=' +divid+ '&bufferid=' +bufferid , {asynchronous:true, evalScripts:true});    }
             
     function hide_a_search(){
            document.getElementById('advanced').style.display='none';
            document.getElementById('time_start').value = 'Starttime';
            document.getElementById('time_end').value = 'Endtime';
        }
