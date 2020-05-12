
//Load toasts/newsfeed for given user
function loadNewToasts($username,$onlySelfPosts){
	let $noMorePosts = $('#posts_area').find('#noMorePosts').val();
	if ($noMorePosts=='true'){
		$('#loading').hide();
		$('.postEndMessage').show();
		// $('#posts_area').find('#noMorePosts').remove();
		return;
	}
	let $page = $('#posts_area').find('#nextPage').val();
	// console.log($page,$username,$noMorePosts)
	$.post("ajax/load_post.php",{
		page:$page,
		onlySelfPosts:$onlySelfPosts,
		username:$username
	}).done(function(result){
		$('#posts_area').find('#noMorePosts').remove();
		$('#posts_area').find('#nextPage').remove();
		$('#posts_area').append(result);
		if ($('#posts_area').find('#noMorePosts').val()=='true'){
			$('#loading').hide();
			$('.postEndMessage').show();
		}
		else{
			//Just in case the buffering thing is still there
			if (checkInView('#loading'))
				loadNewToasts($username,$onlySelfPosts)
		}

	})
}

//Check if an element(string) is in view
function checkInView(element){
	element=$(element)
	let elementOffset = element.offset()

	if (elementOffset)
		return (elementOffset.top + element.innerHeight() >= window.scrollY &&
			elementOffset.top <= window.scrollY + window.innerHeight &&
			elementOffset.left + element.innerWidth() >= window.scrollX &&
			elementOffset.left <= window.scrollX + window.innerWidth)
}

function checkInViewD(element,insideDiv){
	element=document.querySelector(element)
	//element=$(element)
	//let elementOffset = element.offset()
	insideDiv=insideDiv?$(insideDiv):$(window)

	if (element){
		return (element.offsetTop + element.offsetHeight >= insideDiv.scrollTop() &&
			element.offsetTop <= insideDiv.scrollTop() + insideDiv.innerHeight() &&
			element.offsetLeft+ element.offsetWidth >= insideDiv.scrollLeft() &&
			element.offsetLeft <= insideDiv.scrollLeft() + insideDiv.innerWidth())
	}
}

//Time Interval
function time_difference(strtime){
    let today=new Date();
    //strtime is coming from a trusted source, so no checking
    let uptime=new Date(strtime);
    let uyear=uptime.getFullYear();
    let tyear=today.getFullYear();
    let tmonth=today.getMonth()
    let umonth=uptime.getMonth()
    let uday=uptime.getDate();
    let tday=today.getDate();
    let thours=today.getHours()
    let diff="";
    let uhours=uptime.getHours();
    let tmin=today.getMinutes();
    let umin=uptime.getMinutes()
    if (uyear==tyear)
        if (umonth==tmonth)
            if (uday==tday)
                if (uhours==thours)
                    diff= (tmin-umin)+" minute"
                else
                    diff= (thours-uhours)+" hour"
            else
                diff= (tday-uday)+" day";
        else
            diff= (tmonth-umonth)+" month";
    else
        diff= (tyear-uyear)+" year";
    if (diff.substr(0,diff.search(" "))!=="1")
		diff=diff+"s";
	if (diff=="1 day")
		return "Yesterday"
	else
		diff+=" ago"
	if (diff=="0 minutes ago")
		return "Just Now"
	else
		return diff;
}

var lastToggledElement=0;

function toggleElement(element){
	// let target=$(event.target)
	// if (target.is('a') && target.attr('href')!='#') return; //profileLink Clicked;
	element=document.getElementById(element)
	element.style.display=(element.style.display=='block')?'none':'block';
	if (element.style.display=='block' && lastToggledElement!=0 &&lastToggledElement!=element)
		lastToggledElement.style.display='none';
	lastToggledElement=element;
}

function bumpPost($id,$username){
	$.post("ajax/like_post.php",{
		post_id:$id,
		username:$username
	}).done(function(result){
		$(`#likesContainer${$id}`).html(result);
	})
}

function bumpUser($friend_id,$user_id){
	$.post("ajax/add_friend.php",{
		friend:$friend_id,
		user:$user_id
	}).done(function(result){
		$('#friendBtnContainer').html(result);
	})
}

function deleteToast($userOnline,$post_id){
	bootbox.confirm("Are you sure you want to delete this post?", function(result) {
		if (result){
			$.post("ajax/delete_post.php",{
				username:$userOnline,
				post_id:$post_id
			}).done(()=>{
				location.reload();
			})
		}
	})

}

function showFRMsg($username){
	$.post("ajax/getn_friend_request.php",{
		username:$username
	}).done(function(result){
		console.log(result)
		$(`#fr_message`).html(result);
	})
}

function acceptFR($friend_id,$user_id){
	$.post("ajax/accept_friend_request.php",{
		friend:$friend_id,
		user:$user_id
	}).done(function(){
		$(`#requestFrom${$friend_id}`).hide();
		showFRMsg($user_id)
	})
}

function rejectFR($friend_id,$user_id){
	$.post("ajax/reject_friend_request.php",{
		friend:$friend_id,
		user:$user_id
	}).done(function(result){
		console.log(result)
		$(`#requestFrom${$friend_id}`).hide();
		showFRMsg($user_id)
	})
}

//Update all timestamps immediately
function updateTimestamp(){
	$('.timestamp').each(function(){
		let toastTime=$(this).attr('data-time')
		$(this).html(time_difference(String(toastTime)));
	})
}

//Update the timestamps after delay
function refreshTimestamp(delay){
	setTimeout(()=>{
		updateTimestamp();
		refreshTimestamp(delay)
	}, delay)
	// console.log("refreshing")
}

//Live Search users
function liveSearch(username,value,querySelector){
	$.post("ajax/search_friend.php",{
		"username":username,
		"query":value
	}).done(function(data){
		$(querySelector).html(data)
	})
}

//Load notifications for given user
function loadNewNotifsHelper($username,$type){
	
	if ($type!=$('#dropdown_data_type').val()) return;

	let $noMoreNotifs = $('#notifs_area').find('#noMoreNotifs').val();
	if ($noMoreNotifs=='true'){
		$('#notifLoading').hide();
		$('.notifEndMessage').show();
		return;
	}
	let $page = $('#notifs_area').find('#nextNotif').val();
	
	$('#notifs_area').find('#noMoreNotifs').remove();
			$('#notifs_area').find('#nextNotif').remove();
	$.ajax({
		"method":"POST",
		"url":"ajax/"+($type=='messages'?'load_messages.php':'load_notifs.php'),
		"data":{
			page:$page,
			username:$username
		},
		"cache" : false,
		"success":function(result){

			$('#notifs_area').find('#noMoreNotifs').remove();
			$('#notifs_area').find('#nextNotif').remove();

			$('#notifs_area').append(result);
			let $pg = $('#notifs_area').find('#nextNotif').val();
			
			$('#dropdown_data_window').css({"height":Math.min($pg*83,280)})

			if ($('#notifs_area').find('#noMoreNotifs').val()=='true'){
				$('#notifLoading').hide();
				$('.notifEndMessage').show();
				$('.notifEndMessage').html(`No ${$type} to show!`);
				
				if ($pg==1 && $('#notifs_area').children()[0].tagName=="INPUT"){ //i.e. no notification!
					$('.notifEndMessage').css({"margin-top": "50px"});
					$('#dropdown_data_window').css({"height":"140px"})
					$('.notifEndMessage').html(`No ${$type} to show!`);
				}
				else
					$('#dropdown_data_window').css({"height":Math.min($pg*83+68,280)})
			}
		}
	})
}

function loadNewNotifs($username,$type,$delay){
	if ($('#dropdown_data_window').css('visibility')=='visible'&&
		$('#notifLoading').css('display')!='none'){
		setTimeout(()=>{
			if (checkInViewD('#notifLoading','#dropdown_data_window'))
				loadNewNotifsHelper($username,$type)
			loadNewNotifs($username,$type,$delay)
		}, $delay)
	}
}

// var $gettingNotifications=false; //whether we are getting notifications currently

//Get Notifications for messages as well as normal
function getDropDownData($type,$user){
	$('#dropdown_data_type').val($type)
	if ($('#dropdown_data_window').css('visibility')=='hidden'){
		$('.notificationBadge').remove(`#unread_${$type}`) //remove msg notification badge
		$('#dropdown_data_window').css({"visibility":"visible"});

		$('#notifLoading').show();
		$('.notifEndMessage').hide();
		
		loadNewNotifs($user,$type,200)

	} else{
		$('#dropdown_data_type').val("");
		$('#notifLoading').hide()
		$('.notifEndMessage').hide()
		$('#dropdown_data_window').unbind('scroll')
		$('#notifs_area').html(`
					<input type='hidden' id='nextNotif' value='1'/>
					<input type='hidden' id='noMoreNotifs' value='false'/>
		`);
		$('#dropdown_data_window').css({"visibility":"hidden"});
	}
}
