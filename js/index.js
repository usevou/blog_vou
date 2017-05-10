$('#btn_pesq').on('mouseover', function(){
	$('#btn_pesq img').attr('src','imgs/sets/pesq_on.png');
});
$('#btn_pesq').on('mouseout', function(){
	$('#btn_pesq img').attr('src','imgs/sets/pesq.png');
});

$('#btn_pesq').on('click', function(){
	$('#pesq').css('display','block');
	$('#pesq').focus();
	setTimeout(function(){
		$('#pesq').css({'width':'250px','padding':'7px','opacity':'1'});
	},10);
});

$('#pesq').on('focusout', function(){
	if ( $('#pesq').val() == '' ){
		$('#pesq').css({'width':'0px','padding':'7px 0px 7px 0px','opacity':'0'});
		setTimeout(function(){$('#pesq').css('display','none');},500);
	}
});

$('#pesq').keypress(function(e) {
    if(e.which == 13) {

    }
});

$('#social').css('margin-left','calc(50% - '+($('#social').width() / 2)+'px)');

if ( $('#content_tags').width() > $('#tags').width() ){
	$('#next').show();
}

$('#next').on('click',function(){
	$('#tags').animate({
		scrollLeft: ($('#tags').scrollLeft() + 320)
	},800);
	if ( ($('#tags').scrollLeft() + 240) >= ($('#content_tags').width() - $('#tags').width()) ){
		$('#next').hide();
	}
	$('#back').show();
});

$('#back').on('click',function(){
	$('#tags').animate({
		scrollLeft: ($('#tags').scrollLeft() - 320)
	},800);
	if ( ($('#tags').scrollLeft() - 320) <= 0 ){
		$('#back').hide();
	}
	$('#next').show();
});

var limit = 0;
var pesq = null;
var tag = null;

function createFeed(lim,pesq,tag){
	var html = "";
	var w = [2,1,1,1,1,1,2,1,1,1];
	if ( limit == 0 ){
		$('#feed').html("<div id='nothing'><img src='imgs/sets/loading.gif'></div>");
	} else {
		$('#feed').append("<div id='nothing'><img src='imgs/sets/loading.gif'></div>");
	}

	$.ajax({
		type: "POST",
		url: "http://blog.usevou.com/selectPosts.php",
		data: {
				'q': (pesq != null?'pesq':(tag != null?'tag':'normal')),
				'pesq': (pesq != null?pesq:''),
				'tag': (tag != null?tag:''),
				'limit': lim
		},
		async: true,
		dataType: "json",
		success: function (json) {
			$('#nothing').remove();
			if ( limit == 0 ){
				$('#feed').html("");
			}
			if (json[0]['res'] == 1){
				console.log(json);
				for( var i=0;i<json.length;i++ ){
					$('#feed').append('<a href="post.php?id='+json[i]['idpost']+'"><article class="card" style="width:'+(w[i]==1?'33.33333%':'66.66666%')+';height:350px;background-image:url(\''+json[i]['foto']+'\');">'+
						'<div class="fundo_card">'+
							'<div class="content_card">'+
								'<div class="card_title">'+json[i]['titulo']+'</div>'+
								'<div class="date_card">'+json[i]['data']+'</div>'+
								'<div class="autor_card">por '+json[i]['nome']+'</div>'+
							'</div>'+
						'</div>'+
					'</article></a>');
				}
			} else {
				$('#feed').append("<div id='nothing'>Nenhuma postagem encontrada.</div>");
			}
			if ( json.length < 10 ){
				$('#btn_more').hide();
			} else {
				$('#btn_more').show();
			}
		},error: function(xhr,e,t){
			console.log(xhr.responseText);
		}
	});
}

function pesqTag(t){
	tag = t;
	pesq = null;
	limit = 0;
	createFeed(limit,null,tag);
}

function pesquisa(t){
	if ( t != "" ){
		tag = null;
		pesq = t;
		limit = 0;
		createFeed(limit,pesq,null);
	} else {
		pesq = null;
		createFeed(limit,null,null);
	}
}

$('#pesq').keyup(function () {
  pesquisa($(this).val());
});

$('#btn_more').on('click',function(){
	limit += 10;
	if ( pesq != null ){
		createFeed(limit,pesq,null);
	} else if ( tag != null ){
		createFeed(limit,null,tag);
	} else {
		createFeed(limit,null,null);
	}
});



createFeed(limit);
