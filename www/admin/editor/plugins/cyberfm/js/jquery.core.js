/**
 * Cyber Image Manager
 *
 *
 * @package		Cyber Image Manager
 * @author		Radik
 * @copyright	Copyright (c) 2010, Cyber Applications.
 * @link		http://www.cyberapp.ru/
 * @since		Version 1.1
 * @file 		/js/jquery.core.js
 */
 
function Core(){var __this=this;this.__query=null;this.current_path='';this.current_file='';this.lang=new Array();this.conf=new Array();this.files=0;this.ifiles=new Array();this.add_dir=function(){$('.sen-dirs ul').append('<li class="sen-el"><input type="text"></li>');$('.sen-dirs input').focus().bind('save',function(){if($(this).val().match(eval('/[\/\\<>"?*:|]+/'))){__this.alert(__this.lang.error_wrong_dir_name);return false;};path=__this.current_path+$(this).val()+'/';__this.ajax({data:{task:'create_folder',path:path},error:function(XMLHttpRequest,textStatus,errorThrown){__this.alert(lang.error_crete_dir);},success:function(data,textStatus){if(data.done){var dir_name=path.split('/');dir_name=$.grep(dir_name,function(v,i){return i<(dir_name.length-2);});if(dir_name.length!=0){__this.dirs(dir_name.join('/')+'/');}else{__this.dirs('');};}else{__this.alert(__this.lang.error_crete_dir);};}});}).bind('cancel',function(){$(this).parent().remove();}).bind('blur',function(){$(this).triggerHandler('cancel');}).bind('keypress',function(e){if(e.which==13){$(this).triggerHandler('save');}})}
this.dirs=function(root){if(!$.isFunction($.fn.dirs)){__this.alert(__this.lang.error_dirs_init);return false;};$('.sen-dirs').dirs({root:root?root:'',folder_event:__this.onfolder,error_event:function(XMLHttpRequest,textStatus,errorThrown){__this.alert(__this.lang.error_get_dir_list);},script:'index.php?task=get_folder_list',loading:__this.lang.loading});}
this.onfolder=function(dir,page)
{__this.current_path=dir;
if($('#sen-uploader').is(':visible')&&__this.confirm(__this.lang.go_to_files_mode)) {$('#cancel_upload').triggerHandler('click');return false;};
if($('#file_description').is(':visible')) {$('#file_description').hide();};
__this.pathview();
var file_template='<div class="sen-file-wrap">'+'<div class="sen-file-wrap1">'+'<div class="sen-file-wrap2">'+'<div class="sen-file-wrap3">'+'<span rel="{#file_path}">'+'</span>'+'</div>'+'</div>'+'<div class="sen-filename"><img src="pages/default/ext/{#file_ext}.gif" border="0" width="16" height="16">&nbsp;{#file_name}</div>'+'</div>';

$('#sen-list').fadeOut("slow",function(){$('#paginator1').html('');$('#sen-list').html("<div class=\"sen-big-ajax-loader\"></div>").fadeIn("slow",function(){__this.ajax({data:{task:'get_file_list',page:page?page:1,path:dir},error:function(XMLHttpRequest,textStatus,errorThrown){$('.sen-big-ajax-loader').remove();$('#sen-list').html(__this.lang.error_loading_data).fadeIn('slow');},success:function(data,textStatus){$('.sen-big-ajax-loader').remove();$('#sen-list').fadeOut('slow',function(){if(data&&data.files&&data.files.length>0){for(var el=data.files.length-1;el>=0;el--){
var file=file_template;
file=file.replace('{#file_path}',data.files[el].filepath);
file=file.replace('{#file_name}',data.files[el].filename);
file=file.replace('{#file_ext}',data.files[el].fileext);
$('#sen-list').prepend(file).show();};

$('.sen-file-wrap').hover(function(){$(this).addClass('sen-file-wrap-hover');},function(){$(this).removeClass('sen-file-wrap-hover');}).bind('dblclick',function(){__this.current_file=$(this).find('span').attr('rel');try{if(window.parent.tinyMCE||window.top.opener.tinyMCE){if(window.parent.CyberFM){window.parent.CyberFM.execute({Data:__this.current_file,w:window});return true;};if(window.top.opener.CyberFM){window.top.opener.CyberFM.execute({Data:__this.current_file,w:window});return true;};return false;};}catch(e){};try{if(window.top.opener.CKEDITOR){window.top.opener.CyberFM.execute({CKEditorFuncNum:__this.get('CKEditorFuncNum'),Data:__this.current_file});window.top.close();window.top.opener.focus();return true;};}catch(e){};try{if(window.top.opener.SetUrl){window.top.opener.SetUrl(__this.current_file);window.top.close();window.top.opener.focus();return true;};}catch(e){};try{if(window.parent.CyberFM!=='undefined'){window.parent.CyberFM.execute({Data:__this.current_file});return true;};}catch(e){return false;};}).bind('click',function(){__this.current_file=$(this).find('span').attr('rel');$('div.sen-selected-file').removeClass('sen-selected-file');$(this).addClass('sen-selected-file');__this.fileinfo();});}else{$('#sen-list').html(__this.lang.directory_is_empty).fadeIn('slow');}
__this.paginator(data&&data.paginator?data.paginator:false);});}});});});}



this.rename_file=function(){$('#file_description span').each(function(){var name=$(this).text().replace(/\.([a-z]{3,})$/i,'');var ext=$(this).text().replace(eval('/'+name+'\./i'),'');$(this).replaceWith('<input type="text" rel="'+ext+'" value="'+name+'"/>');$('#file_description input').bind('blur',function(){$(this).replaceWith('<span>'+$(this).val()+'.'+$(this).attr('rel')+'</span>');}).bind('keypress',function(e){if(e.which==13){var filename=decodeURI($('div.sen-selected-file span').attr('rel'));__this.ajax({data:{task:'rename_file',old_name:decodeURI(filename),new_name:filename.replace(eval('/[^\\/]+$/i'),$(this).val()+'.'+$(this).attr('rel'))},error:function(XMLHttpRequest,textStatus,errorThrown){__this.alert(__this.lang.error_rename_file);},success:function(data,textStatus){if(data&&data.done){__this.onfolder(__this.current_path);}else{__this.alert(__this.lang.error_rename_file);};}});};}).focus();});}
this.remove_file=function(){var filename=decodeURI(__this.current_file.replace(/^.+\//i,''));if(__this.confirm(__this.lang.remove_file+' '+filename+'?')){__this.ajax({data:{task:'del_file',file:__this.current_path+filename},error:function(XMLHttpRequest,textStatus,errorThrown){__this.alert(__this.lang.error_del_file);},success:function(data,textStatus){if(data&&data.done){__this.onfolder(__this.current_path);}else{__this.alert(__this.lang.error_del_file);};}});};}
__this.download_file=function(){var filename=decodeURI(__this.current_file.replace(/^.+\//i,''));window.open("index.php?task=download_file&file="+encodeURI(__this.current_path+filename));}
this.fileinfo=function(){__this.ajax({data:{task:'get_info',filename:__this.current_file},error:function(XMLHttpRequest,textStatus,errorThrown){__this.alert(__this.lang.error_get_info);},success:function(data,textStatus){
    $('#file_description').hide(1,function(){if(!data)return false;
    var html='<div><b>{#filename}:</b><br><span>{#value}</span></div>'+'<div><b>{#type}:</b><br>{#value}</div>'+'<div><b>{#filesize}:</b><br>{#value}</div>';
    html=html.replace('{#filename}',__this.lang.image_filename);
    html=html.replace('{#value}',decodeURI(__this.current_file.replace(eval('/.+\\//i'),'')));
    html=html.replace('{#type}',__this.lang.image_type).replace('{#value}',data.type);       
    if (data.size<1024) data.size=data.size+' '+__this.lang._byte; else    
    if (data.size>1024) data.size=Math.round(data.size/1024)+' '+__this.lang._kbyte;
    html=html.replace('{#filesize}',__this.lang.image_file_size).replace('{#value}',data.size);
    
    $('#file_desc_data').html(html);$('#file_description').show();});}});}
this.pathview=function(){$('#sen-pathview > div').slice(1).remove();var dirs=__this.current_path.split('/');var path='';var html='';for(var dir=0;dir<dirs.length-1;dir++){path+=dirs[dir]+'/';html+='<div class="sen-pathview-el">';html+='<div class="sen-dir" rel="'+path+'">'+dirs[dir]+'</div>';html+='<div rel="'+path+'" class="sen-edit" title="'+__this.lang.folder_rename+'"></div>';html+='<div rel="'+path+'" class="sen-del" title="'+__this.lang.folder_del+'"></div>';html+='</div>';}
$('#sen-pathview').append(html);$('#sen-pathview').find('div.sen-pathview-el').hover(function(){$(this).addClass('sen-hover')},function(){$(this).removeClass('sen-hover')}).find('.sen-dir').unbind('click').bind('click',function(){var rel=$(this).attr('rel');rel=(typeof rel=='undefined')?'':rel;__this.dirs(rel);__this.onfolder(rel);});$('#sen-pathview .sen-del').bind('del_dir',function(){if(!__this.confirm(__this.lang.confirm_del_dir))
return false;var path=$(this).attr('rel');__this.ajax({data:{task:'del_dir',path:path},error:function(XMLHttpRequest,textStatus,errorThrown){__this.alert(__this.lang.error_del_dir);},success:function(data,textStatus){if(data&&data.done){path=path.split('/');path=$.grep(path,function(v,i){return i<path.length-2})
path=path.length>0?path.join('/')+'/':'';__this.onfolder(path);__this.dirs(path);}else{__this.alert(__this.lang.error_crete_dir);};}});}).bind('click',function(){$(this).triggerHandler('del_dir');});$('#sen-pathview .sen-edit').bind('edit_dir',function(){var path=$(this).attr('rel');_this=$(this).parent();var value=path.split('/')[path.split('/').length-2];var reg=eval("/"+path.replace(eval("/\\//g"),"\\/")+"/gi");var old_inner=$(_this).html().replace(/sen-hover/i,'');$(_this).html('<input type="text" value="'+value+'"/>');$(_this).children('input').one('save',function(e){if(value==$(this).val()){$(this).triggerHandler('cancel');return false;};if($(this).val().match(eval('/[\/\\<>"?*:|]+/'))){__this.alert(__this.lang.error_wrong_dir_name);return false;};$(_this).html('<div class="sen-dir">'+$(this).val()+'</div>');var new_path=path.split('/');new_path[new_path.length-2]=$(this).val();new_path=new_path.join('/');__this.ajax({data:{task:'rename_dir',old_name:path,new_name:new_path},error:function(XMLHttpRequest,textStatus,errorThrown){__this.alert(__this.lang.error_rename_dir);},success:function(data,textStatus){if(data&&data.done){__this.current_path=__this.current_path.replace(path,new_path);__this.onfolder(__this.current_path);__this.dirs(__this.current_path);}else{__this.alert(__this.lang.error_rename_dir);};}});}).bind('cancel',function(){__this.pathview(__this.current_path);}).bind('blur',function(){$(this).triggerHandler('cancel');}).bind('keypress',function(e){if(e.which==13){$(this).triggerHandler('save');};}).focus();}).bind('click',function(e){$(this).triggerHandler('edit_dir');});}
this.paginator=function(o){if(o.pagesTotal<=1||!o){$('#paginator1').hide().html('');return false;}
$('#paginator1').show().paginator({pagesTotal:o.pagesTotal,pagesSpan:10,pageCurrent:o.pageCurrent,baseUrl:function(n){__this.onfolder(__this.current_path,n);},returnOrder:false,lang:{next:__this.lang.paginator_next,last:__this.lang.paginator_last,prior:__this.lang.paginator_prior,first:__this.lang.paginator_first,arrowRight:__this.lang.paginator_arrow_right,arrowLeft:__this.lang.paginator_arrow_left}});}
this.upload_files=function(){__this.files=0;var $upload_button=$('#upload_files');if($upload_button.hasClass('disable'))return false;$upload_button.addClass('disable');function _check_queue(){if(__this.files==0){$('#filesQueue').hide();$('#files_add').removeClass('disable');$('#start_upload').addClass('disable');$('#clear_queue').addClass('disable');return;}
if(__this.files>=__this.conf.queue_size_limit){$('#files_add').addClass('disable');}else{$('#filesQueue:hidden').show();$('#files_add').removeClass('disable');$('#start_upload').removeClass('disable');$('#clear_queue').removeClass('disable');}}
$('#sen-items').slideUp('slow',function(){$('#sen-uploader').slideDown('slow');});var fileTem='<form id="file{#}" class="queueItem" action="index.php?task=upload" enctype="multipart/form-data" target="ajaxFrame" method="post">'+'<div class="fileCancel"><a href="#"></a></div>'+'<input type="file" name="file" class="file" />'+'<input type="hidden" value="'+__this.current_path+'" name="folder">'+'<input type="hidden" value="'+__this.conf.max_file_size+'" name="MAX_FILE_SIZE">'+'<span class="message"></span>'+'</form>';$('#files_add').removeClass('disable').unbind('click').click(function(){if($(this).hasClass('disable'))return false;__this.files++;$('#filesQueue').append(fileTem.replace(eval('/\{#\}/ig'),__this.files));$('#file'+__this.files+' a').one('click',function(){$(this).parent().parent().remove();__this.files=0;$("#sen-uploader form").each(function(){__this.files++;$(this).attr('id','file'+__this.files);})
_check_queue();})
_check_queue();});$('#start_upload').addClass('disable').unbind('click').click(function(){if($(this).hasClass('disable'))return false;var _data={};__this.ifiles=$("#sen-uploader form input:file");_data['folder']=__this.current_path;__this.ifiles.each(function(){_data[$(this).parent().attr('id')]=__this.get_filename($(this).val());});__this.ajax({url:'index.php?task=check_upload',data:_data,success:function(data){for(var i=0;i<__this.ifiles.length;i++){__this.ifiles.eq(i).attr('rel',0);for(var j=0;j<data.length;j++){if(__this.ifiles.eq(i).parent().attr('id').toLowerCase()==data[j].toLowerCase()){__this.ifiles.eq(i).attr('rel','1');break;}}}
__this.continue_upload();}});});$('#clear_queue').addClass('disable').unbind('click').click(function(){if($(this).hasClass('disable'))return false;$('#sen-uploader form').remove();__this.files=0;_check_queue();})
$('#cancel_upload').one('click',function(){$('#sen-uploader').slideUp('slow',function(){$('#sen-items').slideDown('slow',function(){__this.dirs(__this.current_path);__this.onfolder(__this.current_path);$('#sen-uploader form').remove();$upload_button.removeClass('disable');});});});}
this.continue_upload=function(code){function files_shift(){__this.ifiles=__this.ifiles.slice(1);}
function file_status(status,mes){__this.ifiles.eq(0).parent().addClass(status);__this.ifiles.eq(0).parent().find('span.message').text(mes);files_shift();}
switch(code){case 0:file_status('success',__this.lang.upload_completed);break;case 1:file_status('error',__this.lang.upload_error_file_size);break;case 2:file_status('error',__this.lang.upload_error_http);break;case 3:file_status('error',__this.lang.upload_error_no_file);break;case 4:file_status('error',__this.lang.upload_not_allowed_extensions);break;case 5:file_status('error',__this.lang.upload_not_allowed_file_name);break;}
if(__this.ifiles.length>0){upload=true;if(__this.ifiles.eq(0).attr('rel')=='1')
upload=__this.confirm(__this.lang.upload_confirm_replace.replace(/\{#id\}/i,__this.get_filename(__this.ifiles.eq(0).val())));if(upload){__this.ifiles.eq(0).parent().submit();}else{files_shift()
__this.continue_upload();}}}
this.initialize=function(){if(typeof jQuery=='undefined')
return false;$(document).bind('ready',function(){$.getJSON('index.php?task=conf',function(data,textStatus){__this.conf=data;$.getJSON('index.php?task=lang&lang='+__this.conf.lang,function(data,textStatus){__this.lang=data;var html=$('body').html();for(var line in __this.lang){html=html.replace(eval('/{#'+line+'}/g'),__this.lang[line]);}
$('body').html(html);document.title=__this.lang.title;__this.dirs(__this.current_path);__this.onfolder(__this.current_path);$('#folder_add').bind('click',__this.add_dir);$('#upload_files').bind('click',__this.upload_files);$('#rename_file').bind('click',__this.rename_file);$('#remove_file').bind('click',__this.remove_file);$('#download_file').bind('click',__this.download_file);});});});};this.ajax=function(o){if(__this.__query)__this.__query.abort();var data={};$.extend(data,{url:'index.php',async:true,cache:false,dataType:'json',type:'post',error:function(XMLHttpRequest,textStatus,errorThrown){__this.alert('Undefined error');}},o);__this.__query=$.ajax(data);};this.alert=function(mes){return alert(mes);};this.prompt=function(mes){return prompt(mes);};this.confirm=function(mes){return confirm(mes);};this.overlay=function(e){if(e){$(window).resize(function(){$('#overlay').css({width:$(window).width(),height:$(window).height(),left:$('html,body').scrollLeft(),top:$('html,body').scrollTop()});}).triggerHandler('resize');$('#overlay').show();}else{$('#overlay').hide();}}
this.get=function(name){res=eval('/'+name+'=([^#&]*)/').exec(window.location.href);return res[1];}
this.get_filename=function(val){res=eval('/([^\\\\\/]+$)/i').exec(val);return res?res[1]:'';}
this.initialize()}
var CyberCore=new Core();