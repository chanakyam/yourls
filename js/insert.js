// Init some stuff
$(document).ready(function(){
	$('#add-url, #add-keyword').keypress(function(e){
		if (e.which == 13) {add_link();}
	});
	add_link_reset();
	$('#new_url_form').attr('action', 'javascript:add_link();');
	
	$('input.text').focus(function(){
		$(this).select();
	});
	
	// this one actually has little impact, the .hasClass('disabled') in each edit_link_display(), remove() etc... fires faster
	$(document).on( 'click', 'a.button', function() {
		if( $(this).hasClass('disabled') ) {
			return false;
		}
	});
	
	// When Searching, explode search text in pieces -- see split_search_text_before_search()
	$('#filter_form').submit( function(){
		split_search_text_before_search();
		return true;
	});
});

// Create new link and add to table
function add_link() {
	if( $('#add-button').hasClass('disabled') ) {
		return false;
	}
	var newurl = $("#add-url").val();
	var nonce = $("#nonce-add").val();
	if ( !newurl || newurl == 'http://' || newurl == 'https://' ) {
		return;
	}
	var keyword = $("#add-keyword").val();
	add_loading("#add-button");
	$.getJSON(
		ajaxurl,
		{action:'add', url: newurl, keyword: keyword, nonce: nonce},
		function(data){
			if(data.status == 'success') {
				$('#dashboard_main_table tbody').prepend( data.html ).trigger("update");
				$('#nourl_found').css('display', 'none');
				zebra_table();
				increment_counter();
				toggle_share_fill_boxes( data.url.url, data.shorturl, data.url.title );
			}

			add_link_reset();
			end_loading("#add-button");
			end_disable("#add-button");
			//new code for displaying shorturl if already exists
			if(data.status=='fail'){
				feedback(data.shorturl, 'success');				
			}else{
				feedback(data.message, data.status);
			}
			window.setTimeout('location.reload()', 2000); //reloads after 1 seconds
		}
	);
}

// function toggle_share_fill_boxes( url, shorturl, title ) {
// 	$('#copylink').val( shorturl );
// 	// console.log(url);
// 	// console.log(shorturl);
// 	// console.log(title);
// 	$('#titlelink').val( title );
// 	$('#origlink').attr( 'href', url ).html( url );
// 	$('#statlink').attr( 'href', shorturl+'+' ).html( shorturl+'+' );
// 	var tweet = ( title ? title + ' ' + shorturl : shorturl );
// 	$('#tweet_body').val( tweet ).keypress();
// 	$('#shareboxes').slideDown( '300', function(){ init_clipboard(); } ); // clipboard re-initialized after slidedown to make sure the invisible Flash element is correctly positionned
// 	$('#tweet_body').keypress();
// }

function toggle_share_fill_boxes( url, shorturl, title, id ) {
	//new code for show/hide for edit
	var hid_val = $('#show_share').val()
	if($('#share-'+hid_val).length ==1){
		$('#share-'+hid_val).remove()
		$('#show_share').val('');
	}
	var hid_val2 = $('#show_row').val()
	if($('#edit-'+hid_val2).length ==1){
		$('#edit-'+hid_val2).remove()
		//$('#show_share').val('');
	}
	$('#copylink').val( shorturl );
	$('#titlelink').val( title );
	$('#origlink').attr( 'href', url ).html( url );
	$('#statlink').attr( 'href', shorturl+'+' ).html( shorturl+'+' );
	var tweet = ( title ? title + '  ' + shorturl : shorturl );
	$('#tweet_body').val( tweet ).keypress();
	// for updating twitter and facebook links start
	update_share();	
	// end

	// if(shorturl != "")
	// 	tweet += "Short URL: "+ shorturl;
	// if(title != "")
	// 	tweet += "\r\n Long URL: "+ title;
	//$('#tweet_body').val( tweet ).keypress();
	//$('#shareboxes').slideDown( '300', function(){ init_clipboard(); } ); // clipboard re-initialized after slidedown to make sure the invisible Flash element is correctly positionned
	if(id !== undefined && id != ""){
		//var share_content = $('#shareboxes').html();
		//var share_content = '<div id="shareboxes" style="display:none;"><div id="copybox" class="share"><h2>Your short link</h2><p><input id="copylink" class="text width90"  value="" /></p><p><small>Long link: <a id="origlink" href=""></a></small><br/><small>Stats: <a id="statlink" href="+">+</a></small><input type="hidden" id="titlelink" value="" /></p></div><div id="sharebox" class="share"><h2>Quick Share</h2><div id="tweet"><span id="charcount" class="hide-if-no-js">140</span><textarea id="tweet_body"></textarea></div><p id="share_links">Share with <a id="share_tw" href="http://twitter.com/home?status=" title="Tweet this!" onclick="share("tw");return false">Twitter</a><a id="share_fb" href="http://www.facebook.com/share.php?u=" title="Share on Facebook" onclick="share("fb");return false;">Facebook</a><!--<a id="share_ff" href="http://friendfeed.com/share/bookmarklet/frame#title=" title="Share on Friendfeed" onclick="share("ff");return false;">FriendFeed</a>--></p></div></div>';

		
		var share_html = "<tr id='share-"+id+"'><td colspan='6'>"+$('#shareboxes').html()+" <div class='tablecell'><a href='javascript:void(0)' onclick='close_sharebox();' class='close-btn' title='Close'>close[X]</a></div></td></tr>";
		$("#id-" + id).after( share_html );
		var share_id = "#share-"+id; 
		$(share_id).find("#copylink").val(shorturl);
		$(share_id).find("#tweet_body").val(tweet);
				//$("#edit-url-"+ id).focus();
	}

	$('#tweet_body').keypress();
	$('#show_share').val(id);//assigning id to hidden value	
}

function close_sharebox(){
	var s_id = $('#show_share').val();
	$('#share-'+s_id).remove()
}

// Display the edition interface
function edit_link_display(id) {
	if( $('#edit-button-'+id).hasClass('disabled') ) {
		return false;
	}
	
	//new code for show/hide for edit
	var hid_val = $('#show_row').val()
	if($('#edit-'+hid_val).length ==1){
		$('#edit-'+hid_val).remove()
		$('#show_row').val('');
	}
	var hid_val1 = $('#show_share').val()
	if($('#share-'+hid_val1).length ==1){
		$('#share-'+hid_val1).remove()
		//$('#show_share').val('');
	}
	if( $('#edit-'+id).length ==0){
		//code for highlighting the editing row start		
		var tr_class = $('#id-'+id).attr('class');
		$('#id-'+id).removeClass(tr_class);
		$('#id-'+id).addClass("highlight");		
		$('#tr_class').val(tr_class);
		//end	 	
		add_loading('#actions-'+id+' .button');
		var keyword = $('#keyword_'+id).val();
		var nonce = get_var_from_query( $('#edit-button-'+id).attr('href'), 'nonce' );
		$.getJSON(
			ajaxurl,
			{ action: "edit_display", keyword: keyword, nonce: nonce, id: id },
			function(data){
				//new code displaying action icons
				$('#statlink-'+id).removeAttr("disabled")
				$('#statlink-'+id).removeClass("button button_stats disabled")
				$('#statlink-'+id).addClass("button button_stats")

				$('#share-button-'+id).removeAttr("disabled")
				$('#share-button-'+id).removeClass("button button_share disabled")
				$('#share-button-'+id).addClass("button button_share")

				$('#edit-button-'+id).removeAttr("disabled")
				$('#edit-button-'+id).removeClass("button button_edit disabled")
				$('#edit-button-'+id).addClass("button button_edit")

				$('#delete-button-'+id).removeAttr("disabled")
				$('#delete-button-'+id).removeClass("button button_delete disabled")
				$('#delete-button-'+id).addClass("button button_delete")

				$("#id-" + id).after( data.html );
				$("#edit-url-"+ id).focus();
				end_loading('#actions-'+id+' .button');
			}
		);
	}
	//new code for show/hide for edit
	$('#show_row').val(id);
}

// Display the edition interface
function edit_user_display(id) {
	if( $('#edit-button-'+id).hasClass('disabled') ) {
		return false;
	}
	//new code for show/hide for edit
	var hid_val = $('#user_hid').val()
	if($('#edit-'+hid_val).length ==1){
		$('#edit-'+hid_val).remove()
		$('#user_hid').val('');
	}
	//new code
	if( $('#edit-'+id).length ==0){	
		//code for highlighting the editing row start		
		var tr_class = $('#id-'+id).attr('class');
		$('#id-'+id).removeClass(tr_class);
		$('#id-'+id).addClass("highlight");		
		$('#tr_class').val(tr_class);
		//end	 	
		add_loading('#actions-'+id+' .button');
		//var keyword = $('#keyword_'+id).val();
		//var nonce = get_var_from_query( $('#edit-button-'+id).attr('href'), 'nonce' );
		$.getJSON(
			ajaxurl,
			{ action: "edit_user", id: id },
			function(data){
				$("#id-" + id).after( data.html );
				var role = $('#id-'+id+' .user_role').html();
				$('select[name="edit-role-'+id+'"]').find('option[value="'+role+'"]').attr("selected",true);
				var status = $('#id-'+id+' .user_status').html();
				$('select[name="edit-status-'+id+'"]').find('option[value="'+status+'"]').attr("selected",true);
				$("#edit-fname-"+ id).focus();
				end_loading('#actions-'+id+' .button');
			}
		);
	}
	//new code for show/hide for edit
	$('#user_hid').val(id);
}

// Delete a link
function remove_link(id) {
	if( $('#delete-button-'+id).hasClass('disabled') ) {
		return false;
	}
	if (!confirm('Really delete?')) {
		return;
	}
	var keyword = $('#keyword_'+id).val();
	var nonce = get_var_from_query( $('#delete-button-'+id).attr('href'), 'nonce' );
	$.getJSON(
		ajaxurl,
		{ action: "delete", keyword: keyword, nonce: nonce, id: id },
		function(data){
			if (data.success == 1) {
				$("#id-" + id).fadeOut(function(){
					$(this).remove();
					if( $('#dashboard_main_table tbody tr').length  == 1 ) {
						$('#nourl_found').css('display', '');
					}

					zebra_table();
				});
				decrement_counter();
			} else {
				alert('something wrong happened while deleting :/');
			}
		}
	);
	window.setTimeout('location.reload()', 2000); //reloads after 1 seconds
}

// Delete a user link
function remove_user_link(id) {
	if( $('#delete-button-'+id).hasClass('disabled') ) {
		return false;
	}
	if (!confirm('Really delete?')) {
		return;
	}
	//var keyword = $('#keyword_'+id).val();
	//var nonce = get_var_from_query( $('#delete-button-'+id).attr('href'), 'nonce' );
	$.getJSON(
		ajaxurl,
		{ action: "delete_user", id: id },
		function(data){
			if (data.status == 'success') {
				$("#id-" + id).fadeOut(function(){
					$(this).remove();
					if( $('#dashboard_main_table tbody tr').length  == 1 ) {
						$('#nourl_found').css('display', '');
					}

					zebra_table();
				});
				decrement_counter();
			} 
			//window.setTimeout('location.reload()', 3000); //reloads after 1 seconds
			feedback(data.message, data.status);
			
		}
	);
}

// Redirect to stat page
function go_stats(link) {
	window.location=link;
}

// Cancel edition of a link
function edit_link_hide(id) {
	//new code
	$("#edit-" +id).remove();
	// $("#edit-" + id).fadeOut(200, function(){
	// 	end_disable('#actions-'+id+' .button');
	// });
	//code for highlighting the editing row start
	$('#id-'+id).removeClass('highlight');
	$('#id-'+id).addClass($('#tr_class').val());
	//var tr_class = $('#id-'+id).attr('class');
	$('#tr_class').val('');
	//end
}

// Cancel edition of a link
function edit_user_hide(id) {
	//new code
	$("#edit-" +id).remove();
	// $("#edit-" + id).fadeOut(200, function(){
	// 	end_disable('#actions-'+id+' .button');
	// });
	//code for highlighting the editing row start
	$('#id-'+id).removeClass('highlight');
	$('#id-'+id).addClass($('#tr_class').val());
	//var tr_class = $('#id-'+id).attr('class');
	$('#tr_class').val('');
	//end
}

// Save edition of a link
function edit_link_save(id) {
	add_loading("#edit-close-" + id);
	var newurl = encodeURI( $("#edit-url-" + id).val() );
	var newkeyword = $("#edit-keyword-" + id).val();
	var title = $("#edit-title-" + id).val();
	var keyword = $('#old_keyword_'+id).val();
	var nonce = $('#nonce_'+id).val();
	var www = $('#yourls-site').val();
	$.getJSON(
		ajaxurl,
		{action:'edit_save', url: newurl, id: id, keyword: keyword, newkeyword: newkeyword, title: title, nonce: nonce },
		function(data){
			if(data.status == 'success') {
				if( data.url.title != '' ) {
					var display_link = '<a href="' + data.url.url + '" title="' + data.url.url + '">' + data.url.display_title + '</a><br/><small><a href="' + data.url.url + '">' + data.url.display_url + '</a></small>';
				} else {
					var display_link = '<a href="' + data.url.url + '" title="' + data.url.url + '">' + data.url.display_url + '</a>';
				}

				$("#url-" + id).html(display_link);
				$("#keyword-" + id).html('<a href="' + data.url.shorturl + '" title="' + data.url.shorturl + '">' + data.url.keyword + '</a>');
				$("#timestamp-" + id).html(data.url.date);
				$("#edit-" + id).fadeOut(200, function(){
				$('#dashboard_main_table tbody').trigger("update");
				});
				$('#keyword_'+id).val( newkeyword );
				$('#statlink-'+id).attr( 'href', data.url.shorturl+'+' );

				//for updating the table start
				//var link = $('#id-'+id+' .url').html();
				$('#id-'+id+' .keyword').html(newkeyword);
				$('#id-'+id+' .url a').html(title);//link.attr('title');
				var anchor_val = '<a href="'+newurl+'">'+newurl+'</a>'
				$('#longurl-'+id).html(anchor_val);//link.attr('title');
				//end

				//code for highlighting the editing row start
				$('#id-'+id).removeClass('highlight');
				$('#id-'+id).addClass($('#tr_class').val());
				//var tr_class = $('#id-'+id).attr('class');
				$('#tr_class').val('');
				//end

				//var table = $('#dashboard_main_table').dataTable();
				//var pos = table.fnGetPosition( $("#id-"+id)[0] );
				//$('#dashboard_main_table').dataTable().fnUpdate('Zebra' , $('#id-'+id+)[0], 1 );
				//table.fnUpdate(keyword,10,pos);
				// to update a cell
				//table.fnUpdate(keyword);
				//table.fnUpdate(url,2,pos);
				//table.fnUpdate(email,3,pos);
			}
			window.setTimeout('location.reload()', 1000); //reloads after 1 seconds
			feedback(data.message, data.status);
			end_loading("#edit-close-" + id);
			//end_disable("#actions-" + id + ' .button');
			//new code
			$("#edit-" +id).remove();

			//location.reload();
		}
	);
}

// Save edition of a link
// function edit_user_save(id) {
// 	add_loading("#edit-close-" + id);
// 	var fname = $("#edit-fname-" + id).val();
// 	var lname = $("#edit-lname-" + id).val();
// 	var email = $("#edit-email-" + id).val();
// 	//var role = $("#edit-role-" + id).val();
// 	//var status = $("#edit-role-" + id).val();
// 	$.getJSON(
// 		ajaxurl,
// 		{action:'edit_user_save', fname: fname, id: id, lname: lname, email: email  },
// 		function(data){
// 			if(data.status == 'success') {
// 				$("tr-#id"+id+" td.first_name").text(fname);
// 				$("tr-#id"+id+" td.last_name").text(lname);
// 				$("tr-#id"+id+" td.email").text(email);

// 				// $("#url-" + id).html(display_link);
// 				// $("#keyword-" + id).html('<a href="' + data.url.shorturl + '" title="' + data.url.shorturl + '">' + data.url.keyword + '</a>');
// 				// $("#timestamp-" + id).html(data.url.date);
// 				// var table = $('#users_main_table').dataTable();
// 				// var pos = table.fnGetPosition( $("#id-"+id)[0] );
// 				// // table.fnUpdate([id,fname,lname,email],pos);
// 				// // to update a cell
// 				// table.fnUpdate(fname,1,pos);
// 				// table.fnUpdate(lname,2,pos);
// 				// table.fnUpdate(email,3,pos);
// 				//new code for updating values to reflect start
// 				$('#id-'+id+' .first_name').html(fname);
// 				$('#id-'+id+' .last_name').html(lname);
// 				$('#id-'+id+' .user_email').html(email);
// 				//end
// 				$('#users_main_table tbody').trigger("update");
// 				$("#edit-" + id).fadeOut(200, function(){
// 					$('#users_main_table tbody').trigger("update");
// 				});
// 			}
// 			window.setTimeout('location.reload()', 1000); //reloads after 1 seconds
// 			feedback(data.message, data.status);
// 			end_loading("#edit-close-" + id);
// 			//end_disable("#actions-" + id + ' .button');
// 			//new code
// 			$("#edit-" +id).remove();
// 		}
// 	);
// }


function edit_user_save(id) {
	add_loading("#edit-close-" + id);
	var fname  = $("#edit-fname-" + id).val();
	var lname  = $("#edit-lname-" + id).val();
	var email  = $("#edit-email-" + id).val();
	var role   = $("#edit-role-" + id).val();
	var status = $("#edit-status-" + id).val();
	$.getJSON(
		ajaxurl,
		{action:'edit_user_save', fname: fname, id: id, lname: lname, email: email, role: role, status: status},
		function(data){
			if(data.status == 'success') {
				$("tr-#id"+id+" td.first_name").text(fname);
				$("tr-#id"+id+" td.last_name").text(lname);
				$("tr-#id"+id+" td.email").text(email);
				$("tr-#id"+id+" td.role").text(role);
				$("tr-#id"+id+" td.status").val(status);
				// var table = $('#users_main_table').dataTable();
				// var pos = table.fnGetPosition( $("#id-"+id)[0] );
				// table.fnUpdate([id,fname,lname,email,role,status],pos);
				// to update a cell
				// table.fnUpdate(fname,1,pos);
				// table.fnUpdate(lname,2,pos);
				// table.fnUpdate(email,3,pos);
				// table.fnUpdate(role,4,pos);
				// table.fnUpdate(status,5,pos);

				//new code for updating values to reflect start
				$('#id-'+id+' .first_name').html(fname);
				$('#id-'+id+' .last_name').html(lname);
				$('#id-'+id+' .user_email').html(email);
				$('#id-'+id+' .user_role').html(role);
				$('#id-'+id+' .user_status').html(status);
 				//end

				//code for highlighting the editing row start
				$('#id-'+id).removeClass('highlight');
				$('#id-'+id).addClass($('#tr_class').val());
				//var tr_class = $('#id-'+id).attr('class');
				$('#tr_class').val('');
				//end

				$('#users_main_table tbody').trigger("update");
				$("#edit-" + id).fadeOut(200, function(){
				$('#users_main_table tbody').trigger("update");
				});
			}
			feedback(data.message, data.status);
			end_loading("#edit-close-" + id);
			//end_disable("#actions-" + id + ' .button');
			//new code
			$("#edit-" +id).remove();
		}
	);
}

// Prettify table with odd & even rows
function zebra_table() {
	$("#dashboard_main_tablemain_table tbody tr:even").removeClass('odd').addClass('even');
	$("#dashboard_main_tablemain_table tbody tr:odd").removeClass('even').addClass('odd');
	$('#dashboard_main_table tbody').trigger("update");
}

// Ready to add another URL
function add_link_reset() {
	$('#add-url').val('http://').focus();
	$('#add-keyword').val('');
}

// Increment URL counters
function increment_counter() {
	$('.increment').each(function(){
		$(this).html( parseInt($(this).html()) + 1);
	});
}

// Decrement URL counters
function decrement_counter() {
	$('.increment').each(function(){
		$(this).html( parseInt($(this).html()) - 1 );
	});
}

// Toggle Share box
function toggle_share(id) {
	if( $('#share-button-'+id).hasClass('disabled') ) {
		return false;
	}
	//var link =$('#url-'+id+' a: first');
	var link = $('#id-'+id+' .url').html();
	var short_link=$('#id-'+id+' .keyword').html();
	
	
	var longurl = $(link).attr("href");//link.attr('href');
	var title = $('#id-'+id+' .url a').html();//link.attr('title');
	// var url=window.location.href;
	// var host_url=url.split('admin')	
	// var shorturl = host_url[0]+short_link;//$('#keyword-'+id+' a:first').attr('href');
	
	pathArray = window.location.href.split( '/' );
	protocol = pathArray[0];
	host = pathArray[2];
	url = protocol + '//' + host + '/';
	var shorturl = url+short_link;//$('#keyword-'+id+' a:first').attr('href');
	toggle_share_fill_boxes(longurl, shorturl, title ,id);
}

// When "Search" is clicked, split search text to beat servers which don't like query string with "http://"
// See https://github.com/YOURLS/YOURLS/issues/1576
function split_search_text_before_search() {
	// Add 2 hidden fields and populate them with parts of search text
	$("<input type='hidden' name='search_protocol' />").appendTo('#filter_form');
	$("<input type='hidden' name='search_slashes' />").appendTo('#filter_form');
	var search = get_protocol_slashes_and_rest( $('#filter_form input[name=search]').val() );
	$('#filter_form input[name=search]').val( search.rest );
	$('#filter_form input[name=search_protocol]').val( search.protocol );
	$('#filter_form input[name=search_slashes]').val( search.slashes );
}

