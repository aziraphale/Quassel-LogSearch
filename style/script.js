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

     function leer(check){
     }


     function Request(divid,page)     {
             //document.getElementById(divid).innerHTML = '<span id="load" style="display:none;position:absolute; top:5px;left:5px;z-index:99"><img src="style/loading.gif" style="border:1px solid black;"></span>';
             new Ajax.Updater(divid, page, {asynchronous:true, evalScripts:true});}


     function such()     {
        var ary = new Array();

        for(i = 0; i < document.getElementById('buffer').options.length; i++) {
        if(document.getElementById('buffer').options[i].selected) {
            ary.push(document.getElementById('buffer').options[i].value);
        }     }

             //document.getElementById('scontent').innerHTML = '<center><img src="style/loading.gif"></center>';
             new Ajax.Updater('scontent', 'suche.php?search=1&string=' + encodeURIComponent(document.getElementById('input').value) + '&buffername=' + ary + '&number=' + document.getElementById('number').value + '&time_end=' + encodeURIComponent(document.getElementById('time_end').value) + '&time_start=' + encodeURIComponent(document.getElementById('time_start').value) + '&regexid=' + document.getElementById('regexid').checked + '&types=' + document.getElementById('types').checked, {asynchronous:true, evalScripts:true});
             }


     function moreinfo(divid,bufferid,types)     {
             document.getElementById('d'+divid).style.display = 'none';
             document.getElementById('m'+divid).style.display = 'block';
             document.getElementById('m'+divid).style.border = '1px solid #3399cc';
             new Ajax.Updater('m'+divid, 'moreinfo.php?messageid=' +divid+ '&bufferid=' +bufferid+ '&types=' +types , {asynchronous:true, evalScripts:true});    }

     function moreup(msgid,bufferid,state,types){
         new Ajax.Updater('wantmore'+msgid, 'moremore.php?messageid='+document.getElementById(state+msgid).innerHTML+'&bufferid='+bufferid+'&state='+state+'&base='+msgid+ '&types=' +types, {asynchronous:true, evalScripts:true,insertion: Insertion.Top});        
        }

     function moredown(msgid,bufferid,state,types){
         new Ajax.Updater('wantmore'+msgid, 'moremore.php?messageid='+document.getElementById(state+msgid).innerHTML+'&bufferid='+bufferid+'&state='+state+'&base='+msgid+ '&types=' +types, {asynchronous:true, evalScripts:true,insertion: Insertion.Bottom});        
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
        }


     function show_a_search(){
            document.getElementById('asearch').style.display='none';
            document.getElementById('advanced').style.display='inline';
        }


     function multiple(){
            if(document.getElementById('buffer').multiple == true){
                document.getElementById('buffer').style.marginBottom= '0px';
                document.getElementById('buffer').size=1;
                document.getElementById('buffer').multiple=false;
            }else{
                document.getElementById('buffer').style.marginBottom= '-43px';
                document.getElementById('buffer').size=4;
                document.getElementById('buffer').multiple=true;
        }}

      function close_more(messageid){
        document.getElementById('m'+messageid).style.display = 'none';
        document.getElementById('d'+messageid).style.display = 'block';}
