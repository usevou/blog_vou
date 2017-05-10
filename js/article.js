$('#social').css('margin-left','calc(50% - '+($('#social').width() / 2)+'px)');

jQuery("document").ready(function($){
	$(".scroll").click(function(event){
		event.preventDefault();
		$('html,body').animate({
			scrollTop: ($(this.hash).offset().top - 60)
		},800);
	});
});

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
				$('#topo').css('background-image','url("'+e.target.result+'")');
		}
		reader.readAsDataURL(input.files[0]);
	}
}

$("#input_file").change(function(){
	readURL(this);
});

$('#cancel').on('click',function(){window.location.href="home.php?tab=postagem"});

$('#btn_add_tag').on('click',function(){
	var name = $('#input_tag').val();

	if ( name != "" ){
		$.ajax({
		  type: "POST",
		  url: "http://blog.usevou.com/adm/getTagName.php",
		  data: {
		      'q': 'get',
		      'name': name
		  },
		  async: true,
		  dataType: "json",
		  success: function (json) {
		    if (json[0]['res'] == 1){
					$('#quadro_tags').append("<div class='tag' id='tag"+json[0]['id']+"'>#"+ name +
            "<div class='opc_tag'>" +
              "<img src='../imgs/sets/excluir.png' onclick='removeTag("+json[0]['id']+",\""+name+"\")'>"+
            "</div>"+
          "</div>");
					if ( $('#hidden_tags').val() == "" ){
						$('#hidden_tags').val(',' + json[0]['id'] + ',');
					} else {
						$('#hidden_tags').val($('#hidden_tags').val() + '' + json[0]['id'] + ',')
					}
					$('#add_tag'+json[0]['id']).remove();
					$('#input_tag').val('');
		    } else {
					swal({
						title: "Cadastro!",
						text: "Deseja cadastrar essa tag no banco de dados?",
					  showCancelButton: true,
						confirmButtonText: "Sim"
					},
					function(){
						$.ajax({
						  type: "POST",
						  url: "http://blog.usevou.com/adm/getTagName.php",
						  data: {
						      'q': 'ins',
						      'name': name
						  },
						  async: true,
						  dataType: "json",
						  success: function (json) {
						    if (json[0]['res'] == 1){
									$('#quadro_tags').append("<div class='tag' id='tag"+json[0]['id']+"'>#"+ name +
				            "<div class='opc_tag'>" +
				              "<img src='../imgs/sets/excluir.png' onclick='removeTag("+json[0]['id']+",\""+name+"\")'>"+
				            "</div>"+
				          "</div>");
									if ( $('#hidden_tags').val() == "" ){
										$('#hidden_tags').val(',' + json[0]['id'] + ',');
									} else {
										$('#hidden_tags').val($('#hidden_tags').val() + '' + json[0]['id'] + ',')
									}
									$('#input_tag').val('');
						    } else {
									swal({
										title: "Erro!",
										text: "Ocorreu um erro, tente novamente mais tarde."
									});
								}
						  },error: function(xhr,e,t){
						    console.log(xhr.responseText);
						  }
						});
					});
				}
		  },error: function(xhr,e,t){
		    console.log(xhr.responseText);
		  }
		});
	}
});

function removeTag(id,name){
	var value = $("#hidden_tags").val();
	var new_value = value.replace(","+id+",",",");
	$("#hidden_tags").val(new_value);
	$('#tagsname').append('<option id="add_tag'+id+'" value="'+name+'">');
	$('#tag'+id).remove();
}
