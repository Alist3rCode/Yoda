document.body.addEventListener('click', function(){
    var idUser = document.getElementById('id_user');
    $.post("ajax/expireCheck.php", {
			idUser: idUser
	});
});