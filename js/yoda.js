function ucFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


function switchv6() {
    document.getElementById("myDIV").classList.remove("v8");
    document.getElementById("myDIV").classList.remove("v7");
    document.getElementById("myDIV").classList.add("v6");
    document.getElementById("versionHidden").value = 'v6';
}

function switchv7() {
    document.getElementById("myDIV").classList.remove("v8");
    document.getElementById("myDIV").classList.remove("v6");
    document.getElementById("myDIV").classList.add("v7");
    document.getElementById("versionHidden").value = 'v7';
}

function switchv8() {
    document.getElementById("myDIV").classList.remove("v6");
    document.getElementById("myDIV").classList.remove("v7");
    document.getElementById("myDIV").classList.add("v8");
    document.getElementById("versionHidden").value = 'v8';
}


function switchRis(){
    if(document.getElementById('risCheckBox').checked){
        document.getElementById('risHidden').value = 1;
    }else{
        document.getElementById('risHidden').value = 0;
    }
}

function switchPacs(){
    if(document.getElementById('pacsCheckBox').checked){
        document.getElementById('pacsHidden').value = 1;
    }else{
        document.getElementById('pacsHidden').value = 0;
    }
}


function type_ville() {
    var x = document.getElementById("ville");
    var y = document.getElementById("id_ville");
    if (x.value === ''){
        y.innerHTML = "Ville";
    }else{
        y.innerHTML = x.value.toLowerCase();
    
    }
}

function type_nom() {
    var x = document.getElementById("nom");
    var y = document.getElementById("id_nom");
    y.innerHTML = x.value.toLowerCase();
}

$('#create').on('click',function(event){
    while($("#petitTag").length !== 0){
        $("#petitTag").remove();
    }
    
    document.getElementById('myModalLabel').innerHTML = "Création d'un nouveau favori";
    document.getElementById('id').innerHTML = '';
    document.getElementById('buttonSubmit').classList.remove("d-none");
    document.getElementById('buttonModif').classList.add("d-none");
    var version_vignette = document.getElementById('myDIV').className;  
    var ville_mod = document.getElementById('ville');
    var ville_vign = document.getElementById('id_ville');
    
    var nom_mod = document.getElementById('nom');
    var nom_vign = document.getElementById('id_nom');
    
    var url_mod = document.getElementById('url');
    
    var v8_button = document.getElementById('version8Button');
    var v8_mod = document.getElementById('version8');
    
    var v7_mod = document.getElementById('version7');
    var v7_button = document.getElementById('version7Button');
    
    var v6_button = document.getElementById('version6Button');
    var v6_mod = document.getElementById('version6');
    
    var tag_tab_mod = document.getElementById('tags-input');
    var tag_mod = document.getElementById('tag');
    var tag_mod_hidden = document.getElementById('tag_hidden');
    
    
    var suppr = document.getElementById('buttonDelete');
    
    
    
    suppr.classList.add('d-none');
    
    document.getElementById("myDIV").classList.remove(version_vignette);
    document.getElementById("myDIV").classList.add('v7');
    
    ville_mod.value = 'Ville...';
    ville_vign.innerHTML = 'Ville';
    
    nom_mod.value = 'Site Principal...';
    nom_vign.innerHTML = 'Site Principal';
    
    url_mod.value = 'https://...';
    
    if (!v7_button.classList.contains('active')){
        v7_button.classList.add('active');
    }
    if (v6_button.classList.contains('active')){
        v6_button.classList.remove('active');
    }
    if (v8_button.classList.contains('active')){
        v8_button.classList.remove('active');
    }
    tag_mod.value = 'Tags...';
    tag_mod_hidden.value = '';
    
    v7_button.classList.add('active');
    v6_button.classList.remove('active');
    v8_button.classList.remove('active');
    version7.checked = true;
    version6.checked = false;
    version8.checked = false;
}); 

function control_form(mode) {
    var x = document.getElementById('alerte');
    var ville = document.getElementById('ville').value;
    var taille_ville = document.getElementById('taille_ville').value;
    var nom = document.getElementById('nom').value;
    var taille_nom = document.getElementById('taille_nom').value;
    var url = document.getElementById('url').value;
    var version = document.getElementById('versionHidden').value;
    var ris = document.getElementById('risHidden').value;
    var pacs = document.getElementById('pacsHidden').value;
    var id = document.getElementById('id').innerHTML;
    var viewVersion = document.getElementById('viewVersion');
    var uViewVersion = document.getElementById('uViewVersion');
    var imagingVersion = document.getElementById('imagingVersion');
    
    if(uViewVersion.value == ''){
        uViewVersion.value = null;
    }
    if(viewVersion.value == ''){
        viewVersion.value = null;
    }
    
    var regex = /^(https:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*(:\d*)?\/?$/;
    // var regex = /^(https:\/\/.*)(:(\d*))?\/?$/;
    var regex2 = /^(https:\/\/)(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])*(:(\d*))?\/?$/;
    var regex3 = /((?:\+|00)[17](?: |\-)?|(?:\+|00)[1-9]\d{0,2}(?: |\-)?|(?:\+|00)1\-\d{3}(?: |\-)?)?(0\d|\([0-9]{3}\)|[1-9]{0,3})(?:((?: |\-)[0-9]{2}){4}|((?:[0-9]{2}){4})|((?: |\-)[0-9]{3}(?: |\-)[0-9]{4})|([0-9]{7}))/;
    var regex4 = /^(\-?\d+(\.\d+)?).\s*(\-?\d+(\.\d+)?)$/;
    var regexMail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var match = regex.test(url);
    // console.log(match)
    var match2 = regex2.test(url);
    // console.log(match2)

    
    //voir fonction Check plus bas, ça gère les tag
    check();
    var tag = document.getElementById('tag_hidden').value;
    var errors = [];
    var flag = 0;
    // alert(match);

    if (ville === '' || ville == 'Ville...') {
        errors.push('Merci de renseigner un nom de ville');
        flag = 1;

    }

    if (nom === '' || nom == 'Site Principal...') {
        errors.push('Merci de renseigner un nom pour le client');
        flag = 1;
    }

    if (!match && !match2) {
        errors.push('Merci de renseigner un URL valide avec https://');
        flag = 1;
    }
    
    var errorPhone = 0;
    var errorGPS = 0;
    var errorTX = 0;
    var errorMail = 0;
    var listPhone = [];
    var listIdPhone = [];
    var listDelete = [];
    var listLat = [];
    var listLon = [];
    var listMail = [];
    var listTX = [];
    var listIdTV = [];
    var listPasswordTV = [];
    var nbPhone = '';
    var phone = document.getElementsByClassName('phoneClass');
    var lat = document.getElementsByClassName('latClass');
    var lon = document.getElementsByClassName('lonClass');
    var eMail = document.getElementsByClassName('mailClass');
    var TX = document.getElementsByClassName('TXClass');
    var idTV = document.getElementsByClassName('idTVClass');
    var passwordTV = document.getElementsByClassName('passwordTVClass');
    
    // console.log(mode);
    if (mode == 1){
        nbPhone = phone.length;
    }
    if(mode == 2){
        //modif ou suppression de téléphone
        nbPhone = document.getElementById('nbPhone').value;
        //création de téléphone
        if (nbPhone < phone.length){
            if(nbPhone == '0' && phone.length == 1){
                if(phone[0].value != 'Téléphone...' || phone[0].value !== '' ){
                    nbPhone = 1;
                }
            }else{
                nbPhone = phone.length;
            }
        }
        
        
    }
    nbPhone = parseInt(nbPhone);
    
    for (var i = 0; i < nbPhone; i++) {
        if(nbPhone == 1 && phone[i].value == 'Téléphone...'){
            listPhone =[];
            listLat = [];
            listLon = [];
            listMail = [];
            listTX = [];
            listIdTV = [];
            listPasswordTV = [];
            nbPhone = 0; 
        }else{
            var match3 = regex3.test(phone[i].value);
            var matchMail = regexMail.test(eMail[i].value);
            var matchTX1 = regex.test(TX[i].value);
            var matchTX2 = regex2.test(TX[i].value);
            
           
            listIdPhone[i] = document.getElementById('id'+i).value;
            listDelete[i] = document.getElementById('delete'+i).value;
            if (!match3 && phone[i].value != 'Téléphone...') {
                if(errorPhone === 0){
                    errors.push('Merci de renseigner un ou plusieurs numéro(s) de téléphone valide');
                    flag = 1;
                    errorPhone = 1;
                }
            }else{
                listPhone[i] = phone[i].value;
            }
            
            var match4 = regex4.test(lat[i].value);
            var match5 = regex4.test(lon[i].value);
            
            if (!match4 && !match5 && lat[i].value != 'Latitude...' && lon[i].value != 'Longitude...') {
                if(errorGPS === 0){
                    errors.push('Merci de renseigner des coordonnées GPS valides');
                    flag = 1;
                    errorGPS = 1;
                }
            }else{
                listLat[i] = lat[i].value;
                listLon[i] = lon[i].value;
            }
            
            if (!matchMail && eMail[i].value != 'eMail...') {
                if(errorMail === 0){
                    errors.push('Merci de renseigner une ou plusieurs adresse eMail valide');
                    flag = 1;
                    errorMail = 1;
                }
            }else if (eMail[i].value == 'eMail...'){
                listMail[i] = null;
            }else{
                listMail[i] = eMail[i].value;
            }
            
            if (!matchTX1 && !matchTX2) {
                if (TX[i].value != 'Adresse TX...'){
                    if(errorTX === 0){
                        errors.push('Merci de renseigner une ou plusieurs adresse de TX valide');
                        flag = 1;
                        errorTX = 1;
                    }
                }
                
            }else if (TX[i].value == 'Adresse TX...'){
                listTX[i] = null;
            }else{
                listTX[i] = TX[i].value;
            }
            
            if (idTV[i].value == 'ID Teamviewer...'){
                listIdTV[i] = null;
            }else{
                listIdTV[i] = idTV[i].value;
            }
            
            if (passwordTV[i].value == 'Mot de Passe...'){
                listPasswordTV[i] = null;
            }else{
                listPasswordTV[i] = passwordTV[i].value;
            }
           
            
        }
        
    }
    
    var errorSite = 0;
    var site = document.getElementsByClassName('siteClass');
    var listSite= [];
        if (nbPhone > 1){
            for (var i = 0; i < nbPhone; i++) {
                listSite[i] = site[i].value;
                if (site[i].value == 'Site...' || site[i].value == '') {
                    if(errorSite === 0 ){
        
                        errors.push('Merci de renseigner un ou plusieurs nom de site(s) secondaire valide(s)');
                        flag = 1;
                        errorSite = 1;
                    }
                }
        }
    }
    //alert(errors.join());
    if (flag == 1) {
        // alert('plop');
        flag = '';
        $("#alerte").html(errors.join("<br>"));
		$("#alerte").fadeTo(3000, 500).slideUp(500, function() {
			$("#alerte").slideUp(500);
		});
        return {
            'ok' : 0
        };
       
    } else {
       return {
            'ok' : 1,
            'id': id,
            'ville': ville, 
            'nom': nom, 
            'url': url, 
            'version': version, 
            'ris' : ris,
            'pacs' : pacs,
            'tag': tag, 
            'phone': listPhone, 
            'site': listSite, 
            'listIdPhone': listIdPhone,
            'listDelete' : listDelete,
            'nbPhone': nbPhone,
            'lat': listLat,
            'lon': listLon,
            'mail': listMail,
            'TX': listTX,
            'idTV': listIdTV,
            'passwordTV': listPasswordTV,
            'viewVersion': viewVersion.value,
            'uViewVersion' : uViewVersion.value,
            'imagingVersion': imagingVersion.value
        };
    }
}


$('#buttonSubmit').click(function(){
    
    var array = [] ;
    var idUser = document.getElementById('id_user').innerHTML;
    array = control_form(1);
    
    if (array['ok'] == 1){
        $.post("ajax/valid.php", 
            {ville: array['ville'], 
            nom: array['nom'], 
            url: array['url'], 
            version: array['version'], 
            ris: array['ris'], 
            pacs: array['pacs'], 
            tag: array['tag'], 
            phone: array['phone'], 
            site: array['site'], 
            lat: array['lat'], 
            lon: array['lon'], 
            nbPhone: array['nbPhone'], 
            viewVersion: array['viewVersion'], 
            uViewVersion: array['uViewVersion'], 
            imagingVersion: array['imagingVersion'], 
            email : array['mail'], 
            TX : array['TX'], 
            idTV : array['idTV'], 
            passwordTV : array['passwordTV']},
            
            function(ok){
                // console.log(ok);
                result = JSON.parse(ok)
                // console.log(result);
                if(result.result== 'ok'){
                    document.getElementById('nbPhone').value = array['nbPhone'];
                    $.post("ajax/notifMailCreateCustomer.php", 
                    {id: result.id, idUser : idUser});
                  $('#myModal').modal('hide');
                  location.reload();
                }else{
                    console.log(ok)
                }
        });
    }
    
});


$('#buttonModif').click(function(){
    
    var array = [] ;
    array = control_form(2);
    var idUser = document.getElementById('id_user').innerHTML;
    if (array['ok'] == 1){
        // console.log(array['mail'])
        $.post("ajax/modif.php", 
            {id: array['id'], 
            ville: array['ville'], 
            nom: array['nom'], 
            url: array['url'], 
            version: array['version'], 
            ris: array['ris'], 
            pacs: array['pacs'], 
            tag: array['tag'], 
            phone: array['phone'], 
            site: array['site'], 
            lat: array['lat'], 
            lon: array['lon'], 
            nbPhone: array['nbPhone'],
            listIdPhone: array['listIdPhone'], 
            listDelete : array['listDelete'], 
            viewVersion: array['viewVersion'], 
            uViewVersion: array['uViewVersion'], 
            imagingVersion: array['imagingVersion'], 
            email : array['mail'], 
            TX : array['TX'], 
            idTV : array['idTV'], 
            passwordTV : array['passwordTV'] }, 
            function(ok){
                result = JSON.parse(ok)
                if(result.ok== 'ok'){
                    
                  $.post("ajax/notifMailModifCustomer.php", 
                    {avant: result.avant, apres: result.apres, idUser : idUser}); 
                    
                  $('#myModal').modal('hide');
                  location.reload();
                }else{
                    console.log(result)
                }
        });
    }
    
});


$('#buttonDelete').click(function(){
    
    var array = [] ;
    array = control_form(2);
    
    if (array['ok'] == 1){
        $.post("ajax/delete.php", 
            {id: array['id'], 
            ville: array['ville'], 
            nom: array['nom'], 
            url: array['url'], 
            version: array['version'], 
            ris: array['ris'], 
            pacs: array['pacs'], 
            tag: array['tag'], 
            phone: array['phone'], 
            site: array['site'], 
            lat: array['lat'], 
            lon: array['lon'], 
            nbPhone: array['nbPhone'],
            listIdPhone: array['listIdPhone'], 
            viewVersion: array['viewVersion'], 
            uViewVersion: array['uViewVersion'], 
            imagingVersion: array['imagingVersion'], 
            email : array['mail'], 
            TX : array['TX'], 
            idTV : array['idTV'], 
            passwordTV : array['passwordTV']}, 
            function(ok){
                if(ok== 'ok'){
                    
                  $('#myModal').modal('hide');
                  location.reload();
                }else{
                    console.log(ok)
                }
        });
    }
    
});





function check(){
    var ul = document.getElementById("tags-input");
    var items = ul.getElementsByTagName("li");
    document.getElementById("tag_hidden").value = '';
    // console.log(items);
    for (var i = 0; i < items.length; ++i) {
        var span = items[i].getElementsByTagName("span");
        if (typeof span[0] !== 'undefined') {
            // console.log(span);
            document.getElementById("tag_hidden").value = document.getElementById("tag_hidden").value + span[0].innerHTML + ',';
        }
    }
    document.getElementById("tag_hidden").value = document.getElementById("tag_hidden").value.slice(0, -1);

}

function existingTag(text) {
    var existing = false,
        text = text.toLowerCase();

    $(".tags").each(function() {
        if (
            $(this)
            .text()
            .toLowerCase() == text
        ) {
            existing = true;
            return "";
        }
    });

    return existing;
}

$(function tag() {
    $(".tags-new input").click();

    $(".tags-new input").keyup(function() {
        document.getElementById('tag_hidden').value = '';
        var tag = $(this)
            .val()
            .trim(),
            length = tag.length;

        if (tag.charAt(length - 1) == "," && tag != ",") {
            tag = tag.substring(0, length - 1);

            if (!existingTag(tag)) {
                $(
                    '<li class="tags"id="petitTag"><span>' +
                    tag +
                    '</span><i class="fa fa-times"></i></i></li>'
                ).insertBefore($(".tags-new"));
                $(this).val("");
                

            } else {
                $(this).val(tag);
            }
        }
    });
    
    var k = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65],
    n = 0;
    $(document).keydown(function (e) {
        if (e.keyCode === k[n++]) {
            if (n === k.length) {
                window.open('https://www.youtube.com/watch?v=aeePeVUW6-k');
                n = 0;
                return false;
            }
        }
        else {
            n = 0;
        }
    });
    
    $(document).on("click", ".tags i", function() {
        $(this)
            .parent("li")
            .remove();
    });
});

function modif(i){
    while($("#petitTag").length != 0){
        // console.log('remove');
        $("#petitTag").remove();
    }
    
    var suppr = document.getElementById('buttonDelete');
    document.getElementById('id').innerHTML = i;
    
    document.getElementById('myModalLabel').innerHTML = 'Modification du favori'
    
    document.getElementById('buttonSubmit').classList.add("d-none");
    document.getElementById('buttonModif').classList.remove("d-none");
    document.getElementById('buttonDelete').classList.remove("d-none");
    
    var version_vignette = document.getElementById('myDIV').className;  
    var ville_mod = document.getElementById('ville');
    var ville_vign = document.getElementById('id_ville');
   
    var nom_mod = document.getElementById('nom');
    var nom_vign = document.getElementById('id_nom');
    
    var url_mod = document.getElementById('url');
    
    var v8_mod = document.getElementById('version8');
    var v8_button = document.getElementById('version8Button');
    
    var v7_mod = document.getElementById('version7');
    var v7_button = document.getElementById('version7Button');
    
    var v6_button = document.getElementById('version6Button');
    var v6_mod = document.getElementById('version6');
    
    var ris_check = document.getElementById('risCheckBox');
    var ris_button = document.getElementById('risButton');
    
    var pacs_check = document.getElementById('pacsCheckBox');
    var pacs_button = document.getElementById('pacsButton');
    
    var tag_tab_mod = document.getElementById('tags-input');
    var tag_mod = document.getElementById('tag');
    var tag_mod_hidden = document.getElementById('tag_hidden');
    
    var phoneZone = document.getElementById('phones');
    
    var viewVersion = document.getElementById('viewVersion');
    var uViewVersion = document.getElementById('uViewVersion');
    var imagingVersion = document.getElementById('imagingVersion');
    
    
    $.get("ajax/loadVersionImaging.php?id=" + i, function(retour){
        
        imagingVersion.value = retour['version'];
        if(retour['uid'] == 'oui'){
            imagingVersion.disabled = true;
        }else if(retour['uid'] == 'non'){
            imagingVersion.disabled = false;
        }
    });
    
    $.get("ajax/getModif.php?id=" + i, function(json){
        
        viewVersion.value = json['CLI_VIEW'];
        uViewVersion.value = json['CLI_UVIEW'];
        
        console.log(json);
        if(json['nbPhone'] > 1){
            
            for (z=0; z<json['nbPhone']; z++){
               
                if (json['PHO_MAIL'][z] === null || json['PHO_MAIL'][z] === 'NULL' || json['PHO_MAIL'][z] == ''){
                    json['PHO_MAIL'][z] = 'eMail...'
                }
                if (json['PHO_TX'][z] === null || json['PHO_TX'][z] === 'NULL' || json['PHO_TX'][z] == ''){
                    json['PHO_TX'][z] = 'Adresse TX...'
                }
                // console.log(json['PHO_MAIL'][z]);
                newPhone(z);
                document.getElementById('phone'+z).value = json['PHO_PHONE'][z];
                document.getElementById('site'+z).value = ucFirst(json['PHO_SITE'][z]);
                document.getElementById('id'+z).value = json['PHO_ID'][z];
                document.getElementById('lat'+z).value = json['MPS_LAT'][z];
                document.getElementById('lon'+z).value = json['MPS_LON'][z];
                document.getElementById('mail'+z).value = json['PHO_MAIL'][z];
                if(z==0){
                    document.getElementById('TX'+z).value = json['CLI_URL'];
                }else{
                    document.getElementById('TX'+z).value = json['PHO_TX'][z];
                }
                
                if (json['PHO_TV_ID'][z] === null || json['PHO_TV_ID'][z] === 'NULL' || json['PHO_TV_ID'][z] == ''){
                    json['PHO_TV_ID'][z] = 'ID Teamviewer...'
                }
                if (json['PHO_TV_PASSWORD'][z] === null || json['PHO_TV_PASSWORD'][z] === 'NULL' || json['PHO_TV_PASSWORD'][z] == ''){
                    json['PHO_TV_PASSWORD'][z] = 'Mot de Passe...'
                }
                
                document.getElementById('idTV'+z).value = json['PHO_TV_ID'][z];
                document.getElementById('passTV'+z).value = json['PHO_TV_PASSWORD'][z];
                
                // console.log('fin' + z);
            }
            
        }else if (json['nbPhone'] != 0){
            
            if (json['PHO_MAIL'][0] === null || json['PHO_MAIL'][0] === 'NULL' || json['PHO_MAIL'][0] == ''){
                json['PHO_MAIL'][0] = 'eMail...'
            }
            if (json['PHO_TX'][0] === null || json['PHO_TX'][0] === 'NULL' || json['PHO_TX'][0] == ''){
                json['PHO_TX'][0] = 'Adresse TX...'
            }
            document.getElementById('phone0').value = json['PHO_PHONE'][0];
            document.getElementById('id0').value = json['PHO_ID'][0];
            document.getElementById('lat0').value = json['MPS_LAT'][0];
            document.getElementById('lon0').value = json['MPS_LON'][0];
            document.getElementById('mail0').value = json['PHO_MAIL'][0];
            document.getElementById('TX0').value = json['CLI_URL'];
            if (json['PHO_TV_ID'][0] === null || json['PHO_TV_ID'][0] === 'NULL' || json['PHO_TV_ID'][0] == ''){
                json['PHO_TV_ID'][0] = 'ID Teamviewer...'
            }
            if (json['PHO_TV_PASSWORD'][0] === null || json['PHO_TV_PASSWORD'][0] === 'NULL' || json['PHO_TV_PASSWORD'][0] == ''){
                json['PHO_TV_PASSWORD'][0] = 'Mot de Passe...'
            }
            document.getElementById('idTV0').value = json['PHO_TV_ID'][0];
            document.getElementById('passTV0').value = json['PHO_TV_PASSWORD'][0];
            
        }else{
            document.getElementById('phone0').value = 'Téléphone...';
            document.getElementById('site0').value = 'Site...';
            document.getElementById('lat0').value = 'Latitude...';
            document.getElementById('lon0').value = 'Longitude...';
            document.getElementById('mail0').value = 'eMail...';
            document.getElementById('TX0').value = json['CLI_URL'];
            document.getElementById('id0').value = '0';
            document.getElementById('idTV0').value = 'ID Teamviewer...';
            document.getElementById('passTV0').value = 'Mot de Passe...';
            
            
        }
        if(json['nbPhone'] > 1){
            document.getElementById('divPhone' + json['nbPhone']).remove();
            document.getElementById('delete'+ json['nbPhone']).remove();
            document.getElementById('id'+ json['nbPhone']).remove();
            document.getElementById('newPhone'+ (json['nbPhone']-1)).disabled=false;
        }
        document.getElementById('nbPhone').value = json['nbPhone'];
        
        var nbTag = json['CLI_TAG'].length;
        
        suppr.type = 'submit';
        
        ville_vign.innerHTML = json['CLI_VILLE'];
        ville_mod.value = ucFirst(json['CLI_VILLE']);
        
        nom_vign.innerHTML = json['CLI_NOM'];
        nom_mod.value = ucFirst(json['CLI_NOM']);
        
        url_mod.value = json['CLI_URL'];
       
        tag_mod_hidden.value = '';
        for (x=0;x<nbTag;x++){
            if (json['CLI_TAG'][x]!=''){
                $(
                    '<li class="tags"id="petitTag"><span>' +
                    json['CLI_TAG'][x] +
                    '</span><i class="fa fa-times"></i></i></li>'
                ).insertBefore($(".tags-new"));
            
            }
        }
        tag_mod_hidden.value = '';
        
        tag_mod.value = "Tags...";
        
        // console.log(version.className);
        document.getElementById("myDIV").classList.remove(version_vignette);
        document.getElementById("myDIV").classList.add(json['CLI_VERSION']);
    
        // console.log(document.getElementById('myDIV').classname);
        if (json['CLI_VERSION'] == 'v7'){
            // console.log(v7_mod.checked);
            v7_button.classList.add('active');
            v6_button.classList.remove('active');
            v8_button.classList.remove('active');
            version7.checked = true;
            version6.checked = false;
            version8.checked = false;
            document.getElementById('versionHidden').value = 'v7';
            
        }else if (json['CLI_VERSION'] == 'v6'){
            // console.log(v7_mod.checked);
            v6_button.classList.add('active');
            v7_button.classList.remove('active');
            v8_button.classList.remove('active');
            version6.checked = true;
            version7.checked = false;
            version8.checked = false;
            document.getElementById('versionHidden').value = 'v6';
            
        }else if (json['CLI_VERSION'] == 'v8'){
            // console.log(v7_mod.checked);
            v8_button.classList.add('active');
            v7_button.classList.remove('active');
            v6_button.classList.remove('active');
            version8.checked = true;
            version7.checked = false;
            version6.checked = false;
            document.getElementById('versionHidden').value = 'v8';
        }
        
        if (json['CLI_RIS'] == '1'){
            // console.log(v7_mod.checked);
            ris_button.classList.add('active');
            ris_check.checked = true;
            document.getElementById('risHidden').value = '1';
            
        }else{
            // console.log(v7_mod.checked);
            ris_button.classList.remove('active');
            ris_check.checked = false;
            document.getElementById('risHidden').value = '0';
        }
        
        if (json['CLI_PACS'] == '1'){
            // console.log(v7_mod.checked);
            pacs_button.classList.add('active');
            pacs_check.checked = true;
            document.getElementById('pacsHidden').value = '1';
            
        }else{
            // console.log(v7_mod.checked);
            pacs_button.classList.remove('active');
            pacs_check.checked = false;
            document.getElementById('pacsHidden').value = '0';
        }
        
        
    
    });
    
}
    
$('#searchBar').keyup(function(e){
    
    
    $('.collapsePhone').remove();
    $('.phoneIconFa').addClass('fa-phone');
    $('.phoneIconFa').removeClass('fa-chevron-down');
    $('.phoneIconFa').css("color", "black");
    
    
    var search = document.getElementById('searchBar').value.toLowerCase();
    var filter = document.getElementById('filter').innerHTML;
    
    arrayVersion = [];
    vignette = document.getElementsByClassName('vignette');
    
    if (document.getElementById('tabSetV7')){
        if(document.getElementById('searchV7').getAttribute('data-ok') == '1'){
            
            document.getElementById('searchV7').setAttribute('data-ok','0');
        }
        
        $('.bigSearchV7').removeClass('searchActiveV7');
        var listV7 = document.querySelectorAll(".searchV7");

        [].forEach.call(listV7, function(el) {
            el.classList.remove("searchActiveV7");
        });
    }
    if (document.getElementById('tabSetV8')){
        if(document.getElementById('searchV8').getAttribute('data-ok') == '1'){
            
            document.getElementById('searchV8').setAttribute('data-ok','0');
        }
        
        $('.bigSearchV8').removeClass('searchActiveV8');
        var listV8 = document.querySelectorAll(".searchV8");

        [].forEach.call(listV7, function(el) {
            el.classList.remove("searchActiveV8");
        });
    }
    
    
    if (document.getElementById('tabSetV6')){
        if(document.getElementById('searchV6').getAttribute('data-ok') == '1'){
            
            document.getElementById('searchV6').setAttribute('data-ok','0');
        }
        $('.bigSearchV6').removeClass('searchActiveV6');
        var listV6 = document.querySelectorAll(".searchV6");

        [].forEach.call(listV6, function(el) {
            el.classList.remove("searchActiveV6");
        });
    }
    
    if(search == ''){
        
        $('.vignette').removeClass('d-none');
        $('.vignette').removeClass('selectColor');
        idxSelect = 0;
    
    }else{
        $.get("ajax/search.php?search=" + search + "&filter=" + filter, function(json){
               
                $('.vignette').addClass('d-none');
                    
                for (i = 0; i < json.length; i++) {
                    
                    document.getElementById('vignette_' + json[i]).classList.remove('d-none');
                }
                // console.log(json)
    
                // $('.collapsePhone').remove();
                // $('.phoneIconFa').addClass('fa-phone');
                // $('.phoneIconFa').removeClass('fa-chevron-down');
                // $('.phoneIconFa').css("color", "black");
                // $('.vignette').removeClass('selectColor');
                
                if($('.vignette:not(.d-none)').length >= 1){
                    // #francis
                    var list = $('.vignette:not(.d-none)');
                    var idVignette = 0;
                    
                    if (e.keyCode == 39 && idxSelect < json.length-1) {
                    
                        // console.dir(first[idxSelect]); //.next('.vignette:not(.d-none)')
                        $('.vignette:not(.d-none)').removeClass('selectColor');
                        idxSelect = idxSelect + 1;
                        list[idxSelect].classList.add('selectColor');
                        
                    }else if (e.keyCode == 37 && idxSelect > 0) {
                    
                        // console.dir(first[idxSelect]); //.next('.vignette:not(.d-none)')
                        $('.vignette:not(.d-none)').removeClass('selectColor');
                        idxSelect = idxSelect - 1;
                        list[idxSelect].classList.add('selectColor');
                        
                    }else if (e.keyCode == 13 && e.ctrlKey) {
                        
                        list[idxSelect].classList.add('selectColor');
                        idVignette = list[idxSelect].id.substring(9);
                        
                        window.open(document.getElementById(idVignette + '_dbb_url').href);
                        // window.open($('.selectColor a.database').attr('href'));
                        
                    }else if (e.keyCode == 13 && e.altKey) {
                        
                        list[idxSelect].classList.add('selectColor');
                        idVignette = list[idxSelect].id.substring(9);
                        
                        window.open(document.getElementById(idVignette + '_vign_url').href + 'patchmanager/');
                        // window.open($('.selectColor a.database').attr('href'));
                        
                    }else if (e.keyCode == 13) {
                        list[idxSelect].classList.add('selectColor');
                        idVignette = list[idxSelect].id.substring(9);
                        
                        window.open(document.getElementById(idVignette + '_vign_url').href);
                        
                        // window.open($('.selectColor > a').attr('href'));
                        
                    }else if (e.keyCode == 38) {
                        
                        
                        idVignette = list[idxSelect].id.substring(9);
                        if(document.getElementById('phoneIcon_'+ idVignette).classList.contains('fa-chevron-down')){
                            list[idxSelect].classList.add('selectColor');
                            $('#phoneLink_'+ idVignette).click();
                        }
                        
                    }else if (e.keyCode == 40) {
                        
                        
                        idVignette = list[idxSelect].id.substring(9);
                        if(document.getElementById('phoneIcon_'+ idVignette).classList.contains('fa-phone')){
                            list[idxSelect].classList.add('selectColor');
                            $('#phoneLink_'+ idVignette).click();
                        }
                        
                        
                        
                    }else if (e.keyCode == 27) {
                        // console.log(e.keyCode);
                        document.getElementById('searchBar').value ='';
        
                        $('.collapsePhone').remove();
                        $('.phoneIconFa').addClass('fa-phone');
                        $('.phoneIconFa').removeClass('fa-chevron-down');
                        $('.phoneIconFa').css("color", "black");
                  
                        $('.vignette').removeClass('d-none');
                        $('.vignette').removeClass('selectColor');
                        idxSelect = 0;
                        
                    }else{
                        
                        $('.vignette:not(.d-none)').removeClass('selectColor');
                        list[0].classList.add('selectColor');
                        idxSelect = 0;
                        
                        $('.collapsePhone').remove();
                        $('.phoneIconFa').addClass('fa-phone');
                        $('.phoneIconFa').removeClass('fa-chevron-down');
                        $('.phoneIconFa').css("color", "black");
                        
                    }
                    
                }else if (e.keyCode == 27) {
                        // console.log(e.keyCode);
                        document.getElementById('searchBar').value ='';
        
                        $('.collapsePhone').remove();
                        $('.phoneIconFa').addClass('fa-phone');
                        $('.phoneIconFa').removeClass('fa-chevron-down');
                        $('.phoneIconFa').css("color", "black");
                  
                        $('.vignette').removeClass('d-none');
                        $('.vignette').removeClass('selectColor');
                        idxSelect = 0;
                        
                }
        });
    }
    
   
});


$(document).keyup(function(e) {
    if (e.keyCode == 27) {
        // console.log(e.keyCode);
        document.getElementById('searchBar').value ='';
        $('.collapsePhone').remove();
        $('.phoneIconFa').addClass('fa-phone');
        $('.phoneIconFa').removeClass('fa-chevron-down');
        $('.phoneIconFa').css("color", "black");
  
        $('.vignette').removeClass('d-none');
        $('.vignette').removeClass('selectColor');
        idxSelect = 0;
            
    }
    if (e.keyCode == 13 && document.getElementById('myModal').classList.contains('show') && document.getElementById('buttonSubmit').classList.contains('d-none')) {
        e.preventDefault();
        e.stopPropagation();
        $('#buttonModif').click();
            
    }else if (e.keyCode == 13 && document.getElementById('myModal').classList.contains('show') && document.getElementById('buttonModif').classList.contains('d-none')) {
        e.preventDefault();
        e.stopPropagation();
        $('#buttonSubmit').click();
            
    }
});


$('#resetSearch').click(function(){
    idxSelect = 0;
   
    vignette = document.getElementsByClassName('vignette');
    for (y = 0; y < vignette.length; y++) {
        vignette[y].classList.remove('d-none');
    }
    var search = document.getElementById('searchBar').value = '';
    
    if (document.getElementById('tabSetV7')){
        if(document.getElementById('searchV7').getAttribute('data-ok') == '1'){
            
            document.getElementById('searchV7').setAttribute('data-ok','0');
        }
        $('.bigSearchV7').removeClass('searchV7');
        var listV7 = document.querySelectorAll(".searchV7");

        [].forEach.call(listV7, function(el) {
            el.classList.remove("searchActiveV7");
        });
    }
    
    if (document.getElementById('tabSetV6')){
        if(document.getElementById('searchV6').getAttribute('data-ok') == '1'){
            
            document.getElementById('searchV6').setAttribute('data-ok','0');
        }
         $('.bigSearchV6').removeClass('searchV6');
        var listV6 = document.querySelectorAll(".searchV6");

        [].forEach.call(listV6, function(el) {
            el.classList.remove("searchActiveV6");
        });
    }
    
    
    $('.collapsePhone').remove();
    $('.phoneIconFa').addClass('fa-phone');
    $('.phoneIconFa').removeClass('fa-chevron-down');
    $('.phoneIconFa').css("color", "black");
    $('.vignette').removeClass('selectColor');
    
    arrayVersion = [];
    
});

var idxSelect = 0;

$( document ).ready(function(){
    $('#searchBar').focus();
    $("#alerte").hide();

    // console.log(vignette);
    // vignette.forEach(function(element) {
    //     console.log(element.offsetTop);
    // });
    
});



$('#myModal').on('hidden.bs.modal', function (e) {
   while($("#petitTag").length !== 0){
        $("#petitTag").remove();
    }
    
    document.getElementById('myModalLabel').innerHTML = "Rien";
    document.getElementById('id').innerHTML = '';
    
    var version_vignette = document.getElementById('myDIV').className;  
    var ville_mod = document.getElementById('ville');
    var ville_vign = document.getElementById('id_ville');
    
    var nom_mod = document.getElementById('nom');
    var nom_vign = document.getElementById('id_nom');
    
    var url_mod = document.getElementById('url');
    
    var v8_mod = document.getElementById('version8');
    var v8_button = document.getElementById('version8Button');
    
    var v7_mod = document.getElementById('version7');
    var v7_button = document.getElementById('version7Button');
    
    var v6_button = document.getElementById('version6Button');
    var v6_mod = document.getElementById('version6');
    
    var tag_tab_mod = document.getElementById('tags-input');
    var tag_mod = document.getElementById('tag');
    var tag_mod_hidden = document.getElementById('tag_hidden');
    
    var nbPhone = document.getElementById('nbPhone').value;
    
    
    document.getElementById("myDIV").classList.remove(version_vignette);
    document.getElementById("myDIV").classList.add('v7');
    
    ville_mod.value = 'Ville...';
    ville_vign.innerHTML = 'Ville';
    
    nom_mod.value = 'Site Principal...';
    nom_vign.innerHTML = 'Site Principal';
    
    url_mod.value = 'https://...';
    
    if (!v7_button.classList.contains('active')){
        v7_button.classList.add('active');
    }
    if (v6_button.classList.contains('active')){
        v6_button.classList.remove('active');
    }
    if (v8_button.classList.contains('active')){
        v8_button.classList.remove('active');
    }
    tag_mod.value = 'Tags...';
    tag_mod_hidden.value = '';
    
    v7_button.classList.add('active');
    v6_button.classList.remove('active');
    v8_button.classList.remove('active');
    
    version7.checked = true;
    version6.checked = false; 
    version8.checked = false; 
    $('#phones').html('<div id="divPhone0" class="col-12 row" style="border-bottom: solid 1px darkgray;padding: 15px 0;margin-right: 0px;margin-left: 0px;">' +
  
                '<div class="btn-group special col-12" role="group" style="padding-right:0px!important;padding-left:0px!important;" >' + 
                
                    '<button type="button" class="btn btn-outline-success form-group col-1 newPhone"  id="newPhone0" onclick="newPhone(0)"><i class="fa fa-plus"></i></button>' + 
                    
                    
                    '<input class="form-group col-5 siteClass d-none" type="text" name="site0" id="site0" value="Site..." onfocus="if(this.value==\'Site...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Site...\' autocomplete="off" style=" margin:0 0 5px 0; height:38px;">' + 
                    
                    '<input class="form-group col-10 phoneClass" type="text" name="phone0" id="phone0" value="Téléphone..." onfocus="if(this.value==\'Téléphone...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Téléphone...\' autocomplete="off" style=" margin:0 0 5px 0; height:38px;">' + 
                    
                    
                    '<button type="button" class="btn btn-outline-secondary form-group col-1 deletePhone" disabled id="deletePhone0" onclick="deletePhone(0)"><i class="fa fa-trash-o"></i></button>' + 
                '</div>' + 
                
                '<div class="btn-group special col-md-6" style="height:38px;padding-left:0px;padding-right:0px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled"  disabled type="button" style="height:38px;"><i class="fa fa-map"></i></button>'+
                        '<input class="form-control col-md-10 latClass" type="text" name="lat0" id="lat0" value="Latitude..." onfocus="if(this.value==\'Latitude...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Latitude...\'" autocomplete="off" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                     
                    '<div class="btn-group special col-md-6" style="height:38px;padding-left:0px;padding-right:0px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled" disabled type="button" style="height:38px;"><i class="fa fa-map-o"></i></button>'+       
                        '<input class="form-control col-md-10 lonClass" type="text" name="lon0" id="lon0" value="Longitude..." onfocus="if(this.value==\'Longitude...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Longitude...\'" autocomplete="off" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                    
                    '<div class="btn-group special col-md-6" style="height:38px;padding-left:0px;padding-right:0px;margin-top:5px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled" disabled  type="button" style="height:38px;"><i class="fa fa-envelope-o"></i></button>'+     
                        '<input class="form-control col-md-10 mailClass" type="text" name="mail0" id="mail0" value="eMail..." onfocus="if(this.value==\'eMail...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'eMail...\'" autocomplete="off" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                            
                    '<div class="btn-group special col-md-6" style="height:38px;margin-top:5px;padding-left:0px;padding-right:0px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled"  disabled type="button" style="height:38px;"><i class="fa fa-external-link"></i></button>'+
                        '<input type="text" class="form-control col-10 TXClass" name="TX0" id="TX0" value="Adresse TX..." onfocus="if(this.value==\'Adresse TX...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Adresse TX...\'" autocomplete="off" aria-label="" aria-describedby="basic-addon1" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                    
                    '<div class="btn-group special col-md-6" style="height:38px;padding-left:0px;padding-right:0px;margin-top:5px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled" disabled  type="button" style="height:38px;"><i class="fa fa-id-card-o"></i></button> '+    
                        '<input class="form-control col-md-10 idTVClass" type="text" name="idTV0" id="idTV0" value="ID Teamviewer..." onfocus="if(this.value==\'ID Teamviewer...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'ID Teamviewer...\'" autocomplete="off" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                            
                    '<div class="btn-group special col-md-6" style="height:38px;margin-top:5px;padding-left:0px;padding-right:0px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled"  disabled type="button" style="height:38px;"><i class="fa fa-unlock-alt"></i></button>'+
                        '<input type="text" class="form-control col-10 passwordTVClass" name="passTV0" id="passTV0"  value="Mot de Passe..." onfocus="if(this.value==\'Mot de Passe...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Mot de Passe...\'" autocomplete="off" aria-label="" aria-describedby="basic-addon1" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                
            '</div>' + 
            
            '<input type="hidden" value ="1" id="delete0" name="delete0">' +
            '<input type="hidden" value ="" id="id0" name="id0">');
                        
    document.getElementById('alerte').innerHTML = '';
                        
    
});


function newPhone(i) {
   
   
    var newPhone = document.getElementsByClassName('newPhone');
    var phone = document.getElementsByClassName('phoneClass');
    var site = document.getElementsByClassName('siteClass');
    var deletePhone = document.getElementsByClassName('deletePhone');
    var phoneDisplayed = newPhone.length;
    var survivor = 0;
    
    for(y=0; y < newPhone.length; y++){
        if(document.getElementById('divPhone' + y).classList.contains('d-none')){
            phoneDisplayed = phoneDisplayed - 1;
        }else{
            survivor = y;
        }
    }
    
    if(phoneDisplayed == 1){
        document.getElementById('phone' + survivor).classList.remove("col-10");
        document.getElementById('phone' + survivor).classList.add("col-5");
        document.getElementById('site' + survivor).classList.remove("d-none");
        document.getElementById('deletePhone' + survivor).disabled = false;
        document.getElementById('deletePhone' + survivor).classList.remove("btn-outline-secondary");
        document.getElementById('deletePhone' + survivor).classList.add("btn-outline-danger");
   }
    
    
    
//   if(newPhone.length == 1){
//         phone[0].classList.remove("col-10");
//         phone[0].classList.add("col-5");
//         site[0].classList.remove("d-none");
//         deletePhone[0].disabled = false;
//         deletePhone[0].classList.remove("btn-outline-secondary");
//         deletePhone[0].classList.add("btn-outline-danger");
//   }
  document.getElementById('newPhone'+i).disabled=true;
  
  x = newPhone.length ;
  var str = '<div id="divPhone' + x + '" class="col-12 row" style="border-bottom: solid 1px darkgray;padding: 15px 0;margin-right: 0px;margin-left: 0px;">' +
  
                '<div class="btn-group special col-12" role="group" style="padding-right:0px!important;padding-left:0px!important;" >' + 
                
                    '<button type="button" class="btn btn-outline-success form-group col-1 newPhone"  id="newPhone' + x + '" onclick="newPhone(' + x + ')"><i class="fa fa-plus"></i></button>' + 
                    
                    
                    '<input class="form-group col-5 siteClass" type="text" name="site' + x + '" id="site' + x + '" value="Site..." onfocus="if(this.value==\'Site...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Site...\' autocomplete="off" style=" margin:0 0 5px 0; height:38px;">' + 
                    
                    '<input class="form-group col-5 phoneClass" type="text" name="phone' + x + '" id="phone' + x + '" value="Téléphone..." onfocus="if(this.value==\'Téléphone...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Téléphone...\' autocomplete="off" style=" margin:0 0 5px 0; height:38px;">' + 
                    
                    
                    '<button type="button" class="btn btn-outline-danger form-group col-1 deletePhone" id="deletePhone' + x + '" onclick="deletePhone(' + x + ')"><i class="fa fa-trash-o"></i></button>' + 
                '</div>' + 
                
                '<div class="btn-group special col-md-6" style="height:38px;padding-left:0px;padding-right:0px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled"  disabled type="button" style="height:38px;"><i class="fa fa-map"></i></button>'+
                        '<input class="form-control col-md-10 latClass" type="text" name="lat' + x + '" id="lat' + x + '" value="Latitude..." onfocus="if(this.value==\'Latitude...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Latitude...\'" autocomplete="off" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                     
                    '<div class="btn-group special col-md-6" style="height:38px;padding-left:0px;padding-right:0px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled" disabled type="button" style="height:38px;"><i class="fa fa-map-o"></i></button>'+       
                        '<input class="form-control col-md-10 lonClass" type="text" name="lon' + x + '" id="lon' + x + '" value="Longitude..." onfocus="if(this.value==\'Longitude...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Longitude...\'" autocomplete="off" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                    
                    '<div class="btn-group special col-md-6" style="height:38px;padding-left:0px;padding-right:0px;margin-top:5px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled" disabled  type="button" style="height:38px;"><i class="fa fa-envelope-o"></i></button>'+     
                        '<input class="form-control col-md-10 mailClass" type="text" name="mail' + x + '" id="mail' + x + '" value="eMail..." onfocus="if(this.value==\'eMail...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'eMail...\'" autocomplete="off" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                            
                    '<div class="btn-group special col-md-6" style="height:38px;margin-top:5px;padding-left:0px;padding-right:0px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled"  disabled type="button" style="height:38px;"><i class="fa fa-external-link"></i></button>'+
                        '<input type="text" class="form-control col-10 TXClass" name="TX' + x + '" id="TX' + x + '" value="Adresse TX..." onfocus="if(this.value==\'Adresse TX...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Adresse TX...\'" autocomplete="off" aria-label="" aria-describedby="basic-addon1" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                    
                      '<div class="btn-group special col-md-6" style="height:38px;padding-left:0px;padding-right:0px;margin-top:5px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled" disabled  type="button" style="height:38px;"><i class="fa fa-id-card-o"></i></button> '+    
                        '<input class="form-control col-md-10 idTVClass" type="text" name="idTV' + x + '" id="idTV' + x + '" value="ID Teamviewer..." onfocus="if(this.value==\'ID Teamviewer...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'ID Teamviewer...\'" autocomplete="off" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                            
                    '<div class="btn-group special col-md-6" style="height:38px;margin-top:5px;padding-left:0px;padding-right:0px;" role="group">'+
                        '<button class="btn btn-outline-primary form-group disabled"  disabled type="button" style="height:38px;"><i class="fa fa-unlock-alt"></i></button>'+
                        '<input type="text" class="form-control col-10 passwordTVClass" name="passTV' + x + '" id="passTV' + x + '"  value="Mot de Passe..." onfocus="if(this.value==\'Mot de Passe...\')this.value=\'\'"  onblur="if(this.value==\'\')this.value=\'Mot de Passe...\'" autocomplete="off" aria-label="" aria-describedby="basic-addon1" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">'+
                    '</div>'+
                
            '</div>' + 
            '<input type="hidden" value ="1" id="delete' + x + '" name="delete' + x + '">' +
            '<input type="hidden" value ="" id="id' + x + '" name="id' + x + '">';

document.getElementById('phones').insertAdjacentHTML( 'beforeend', str );
}


function deletePhone(i){
   
    
    document.getElementById('divPhone' + i).classList.add('d-none');
    document.getElementById('delete' + i).value = 0;
    
    var newPhone = document.getElementsByClassName('newPhone');
    var phone = document.getElementsByClassName('phoneClass');
    var site = document.getElementsByClassName('siteClass');
    var deletePhone = document.getElementsByClassName('deletePhone');
    var phoneDisplayed = newPhone.length;
    var survivor = 0;
    for(y=0; y < newPhone.length; y++){
        if(document.getElementById('divPhone' + y).classList.contains('d-none')){
            phoneDisplayed = phoneDisplayed - 1;
        }else{
            survivor = y;
        }
    }
    
    if(phoneDisplayed == 1){
        document.getElementById('phone' + survivor).classList.add("col-10");
        document.getElementById('phone' + survivor).classList.remove("col-5");
        document.getElementById('site' + survivor).classList.add("d-none");
        document.getElementById('deletePhone' + survivor).disabled = true;
        document.getElementById('deletePhone' + survivor).classList.add("btn-outline-secondary");
        document.getElementById('deletePhone' + survivor).classList.remove("btn-outline-danger");
   }
   
   document.getElementById('newPhone' + survivor).disabled=false;
}   


$('.phoneIconLink').click(function(evt){
    
    var idx = this.id.substring(10);
    var collapseDiv = document.getElementById('collapse_' + idx);
    console.log(document.getElementById('vignette_' + idx).offsetTop);
    $('.collapsePhone').remove();
    
    if(!collapseDiv){
        var location = document.getElementById('vignette_' + idx).offsetTop;
        var newLocation = 0;
        var flag = 0;
        $( ".vignette" ).each(function( index ) {
            if(this.offsetTop-2 > location  && flag == 0){
                newLocation = this.id.substring(9);
                
                flag = 1;
            }
        });
        var bookmarks = document.getElementById('bookmarks');
        var newItem = document.createElement("div"); 
        newItem.id = 'collapse_' + idx;
        newItem.classList.add('collapsePhone');
    
        bookmarks.insertBefore(newItem, document.getElementById('vignette_' + newLocation) );
        $("#collapse_" + idx).load("ajax/loadPhone.php?id=" + idx + "&user="+ document.getElementById('id_user').innerHTML); 
         
        collapseDiv = document.getElementById('collapse_' + idx);
    }
    
        
    $('.phoneIconFa').addClass('fa-phone');
    $('.phoneIconFa').removeClass('fa-chevron-down');
    $('.phoneIconFa').css("color", "black")
    
    if(!collapseDiv.classList.contains('show')){
        document.getElementById('phoneIcon_' + idx).classList.remove('fa-phone');
        document.getElementById('phoneIcon_' + idx).classList.add('fa-chevron-down');
        document.getElementById('phoneIcon_' + idx).style.color='red';
        collapseDiv.classList.add('show');
    }else{
        document.getElementById('phoneIcon_' + idx).classList.add('fa-phone');
        document.getElementById('phoneIcon_' + idx).classList.remove('fa-chevron-down');
        document.getElementById('phoneIcon_' + idx).style.color='black';
        $('#phone_' + idx).remove();
        
    }
    
    // 
    
});


$('[data-toggle="tooltip"]').tooltip();

$('[data-toggle="tooltip"]').each(function(index, element){
    
    var tg = $(element);
    var idx = element.getAttribute('data-id');
        $.get("ajax/loadVersion.php?id=" + idx, function(data) {
            tg.attr('data-original-title', data);
        });
});




$('#tabSetV6').scrollTabs({
      scroll_distance: 150,
      scroll_duration: 350,
      left_arrow_size: 26,
      right_arrow_size: 26,
      click_callback: function(e){
        var val = $(this).attr('rel');
        if(val){
          window.location.href = val;
        }
    }
});



$('#tabSetV7').scrollTabs({
      scroll_distance: 150,
      scroll_duration: 350,
      left_arrow_size: 26,
      right_arrow_size: 26,
      click_callback: function(e){
        var val = $(this).attr('rel');
        if(val){
          window.location.href = val;
        }
    }
});

$('#tabSetV8').scrollTabs({
      scroll_distance: 150,
      scroll_duration: 350,
      left_arrow_size: 26,
      right_arrow_size: 26,
      click_callback: function(e){
        var val = $(this).attr('rel');
        if(val){
          window.location.href = val;
        }
    }
});


$('#tabSetActivity').scrollTabs({
      scroll_distance: 150,
      scroll_duration: 350,
      left_arrow_size: 26,
      right_arrow_size: 26,
      click_callback: function(e){
        var val = $(this).attr('rel');
        if(val){
          window.location.href = val;
        }
    }
});


var arrayVersion = [];

function displaySearch(){

    
    if(arrayVersion.length == 0){
            vignette = document.getElementsByClassName('vignette');
            for (y = 0; y < vignette.length; y++) {
                vignette[y].classList.remove('d-none');
            }
    }else{        
            
                $.post("ajax/searchVersion.php",
                {search: arrayVersion}, 
                function(json){
                        vignette = document.getElementsByClassName('vignette');
                            for (y = 0; y < vignette.length; y++) {
                                vignette[y].classList.add('d-none');
                            }
                        for (i = 0; i < json.length; i++) {
                            
                            document.getElementById('vignette_' + json[i]).classList.remove('d-none');
                        }
                });
        
    }
    
    
    $('.collapsePhone').remove();
    $('.phoneIconFa').addClass('fa-phone');
    $('.phoneIconFa').removeClass('fa-chevron-down');
    $('.phoneIconFa').css("color", "black")
    
}


function searchVersionV7(i){
    
    $('.collapsePhone').remove();
    document.getElementById("searchBar").value = "";
    $('.vignette').removeClass('selectColor');
    
    if(document.getElementById('searchV7').getAttribute('data-ok') == '1'){
        
        document.getElementById('searchV7').classList.remove('searchActiveV7');
        document.getElementById('searchV7').setAttribute('data-ok','0');
    }
    
    
    if (arrayVersion.includes(i)){
        var index = arrayVersion.indexOf(i);
        if (index > -1) {
            arrayVersion.splice(index, 1);
        }
    document.getElementById('searchVersion_' + i).classList.remove("searchActiveV7");
    }else{
        document.getElementById('searchVersion_' + i).classList.add("searchActiveV7");
        arrayVersion.push(i);
    }
    
    console.log(arrayVersion);
    
    displaySearch();
}

function searchVersionV8(i){
    
    $('.collapsePhone').remove();
    document.getElementById("searchBar").value = "";
    $('.vignette').removeClass('selectColor');
    
    if(document.getElementById('searchV8').getAttribute('data-ok') == '1'){
        
        document.getElementById('searchV8').classList.remove('searchActiveV8');
        document.getElementById('searchV8').setAttribute('data-ok','0');
    }
    
    
    if (arrayVersion.includes(i)){
        var index = arrayVersion.indexOf(i);
        if (index > -1) {
            arrayVersion.splice(index, 1);
        }
    document.getElementById('searchVersion_' + i).classList.remove("searchActiveV8");
    }else{
        document.getElementById('searchVersion_' + i).classList.add("searchActiveV8");
        arrayVersion.push(i);
    }
    
    console.log(arrayVersion);
    
    displaySearch();
}


$('#searchV7').click(function(){
    
    $('.collapsePhone').remove();
    document.getElementById("searchBar").value = "";
     $('.vignette').removeClass('selectColor');
    arrayVersion = [];
    if (document.getElementById('tabSetV6')){
        if(document.getElementById('searchV6').getAttribute('data-ok') == '1'){
            
            document.getElementById('searchV6').classList.remove('searchActiveV6');
            document.getElementById('searchV6').setAttribute('data-ok','0');
        }
        $('.searchV6').each(function(index, element){
            console.dir(element);
                $(element).removeClass("searchActiveV6");
                var index = arrayVersion.indexOf(element.innerHTML);
                if (index > -1) {
                    arrayVersion.splice(index, 1);
                }
                
        });
    }
    if (document.getElementById('tabSetV8')){
        if(document.getElementById('searchV8').getAttribute('data-ok') == '1'){
            
            document.getElementById('searchV8').classList.remove('searchActiveV8');
            document.getElementById('searchV8').setAttribute('data-ok','0');
        }
        $('.searchV8').each(function(index, element){
            console.dir(element);
                $(element).removeClass("searchActiveV8");
                var index = arrayVersion.indexOf(element.innerHTML);
                if (index > -1) {
                    arrayVersion.splice(index, 1);
                }
                
        });
    }
    
    if(document.getElementById('searchV7').getAttribute('data-ok') == '0'){
        $('.searchV7').each(function(){
            $(this).addClass("searchActiveV7");
            arrayVersion.push($(this).html());
        });
        document.getElementById('searchV7').classList.add('searchActiveV7');
        document.getElementById('searchV7').setAttribute('data-ok', '1');
    }else if(document.getElementById('searchV7').getAttribute('data-ok') == '1'){
        $('.searchV7').each(function(){
            $(this).removeClass("searchActiveV7");
        });
        document.getElementById('searchV7').classList.remove('searchActiveV7');
        document.getElementById('searchV7').setAttribute('data-ok','0');
    }
    
     displaySearch();
    
});

$('#searchV8').click(function(){
    
    $('.collapsePhone').remove();
    document.getElementById("searchBar").value = "";
     $('.vignette').removeClass('selectColor');
    arrayVersion = [];
    if (document.getElementById('tabSetV6')){
        if(document.getElementById('searchV6').getAttribute('data-ok') == '1'){
            
            document.getElementById('searchV6').classList.remove('searchActiveV6');
            document.getElementById('searchV6').setAttribute('data-ok','0');
        }
        $('.searchV6').each(function(index, element){
            console.dir(element);
                $(element).removeClass("searchActiveV6");
                var index = arrayVersion.indexOf(element.innerHTML);
                if (index > -1) {
                    arrayVersion.splice(index, 1);
                }
                
        });
    }
    if (document.getElementById('tabSetV7')){
        if(document.getElementById('searchV7').getAttribute('data-ok') == '1'){
            
            document.getElementById('searchV7').classList.remove('searchActiveV7');
            document.getElementById('searchV7').setAttribute('data-ok','0');
        }
        $('.searchV7').each(function(index, element){
            console.dir(element);
                $(element).removeClass("searchActiveV7");
                var index = arrayVersion.indexOf(element.innerHTML);
                if (index > -1) {
                    arrayVersion.splice(index, 1);
                }
                
        });
    }
    
    if(document.getElementById('searchV8').getAttribute('data-ok') == '0'){
        $('.searchV8').each(function(){
            $(this).addClass("searchActiveV8");
            arrayVersion.push($(this).html());
        });
        document.getElementById('searchV8').classList.add('searchActiveV8');
        document.getElementById('searchV8').setAttribute('data-ok', '1');
    }else if(document.getElementById('searchV8').getAttribute('data-ok') == '1'){
        $('.searchV8').each(function(){
            $(this).removeClass("searchActiveV8");
        });
        document.getElementById('searchV8').classList.remove('searchActiveV8');
        document.getElementById('searchV8').setAttribute('data-ok','0');
    }
    
     displaySearch();
    
});



function searchVersionV6(i){
    document.getElementById("searchBar").value = "";
     $('.vignette').removeClass('selectColor');
    $('.collapsePhone').remove();
    
    if(document.getElementById('searchV6').getAttribute('data-ok') == '1'){
        
        document.getElementById('searchV6').classList.remove('searchActiveV6');
        document.getElementById('searchV6').setAttribute('data-ok','0');
    }
    
    
    if (arrayVersion.includes(i)){
        var index = arrayVersion.indexOf(i);
        if (index > -1) {
            arrayVersion.splice(index, 1);
        }
    document.getElementById('searchVersion_' + i).classList.remove("searchActiveV6");
    }else{
        document.getElementById('searchVersion_' + i).classList.add("searchActiveV6");
        arrayVersion.push(i);
    }
    
    console.log(arrayVersion);
    
    displaySearch();
}

$('#searchV6').click(function(){
    document.getElementById("searchBar").value = "";
     $('.vignette').removeClass('selectColor');
    $('.collapsePhone').remove();
    
    arrayVersion = [];
    
    if (document.getElementById('tabSetV7')){
        if(document.getElementById('searchV7').getAttribute('data-ok') == '1'){
        
            document.getElementById('searchV7').classList.remove('searchActiveV7');
            document.getElementById('searchV7').setAttribute('data-ok','0');
        }
        $('.searchV7').each(function(index, element){
            console.dir(element);
            $(element).removeClass("searchActiveV7");
            var index = arrayVersion.indexOf(element.innerHTML);
            if (index > -1) {
                arrayVersion.splice(index, 1);
            }
            
        });
    }
    if (document.getElementById('tabSetV8')){
        if(document.getElementById('searchV8').getAttribute('data-ok') == '1'){
        
            document.getElementById('searchV8').classList.remove('searchActiveV8');
            document.getElementById('searchV8').setAttribute('data-ok','0');
        }
        $('.searchV8').each(function(index, element){
            console.dir(element);
            $(element).removeClass("searchActiveV8");
            var index = arrayVersion.indexOf(element.innerHTML);
            if (index > -1) {
                arrayVersion.splice(index, 1);
            }
            
        });
    }
    
    if(document.getElementById('searchV6').getAttribute('data-ok') == '0'){
        $('.searchV6').each(function(){
            $(this).addClass("searchActiveV6");
            arrayVersion.push($(this).html());
        });
        document.getElementById('searchV6').classList.add('searchActiveV6');
        document.getElementById('searchV6').setAttribute('data-ok', '1');
    }else if(document.getElementById('searchV6').getAttribute('data-ok') == '1'){
        $('.searchV6').each(function(){
            $(this).removeClass("searchActiveV6");
        });
        document.getElementById('searchV6').classList.remove('searchActiveV6');
        document.getElementById('searchV6').setAttribute('data-ok','0');
    }
    
     displaySearch();
    
});


function searchActivity(i){
    
    $('.collapsePhone').remove();
    $('.vignette').removeClass('selectColor');
    document.getElementById("searchBar").value = "";
    
    if (arrayVersion.includes(i)){
        var index = arrayVersion.indexOf(i);
        if (index > -1) {
            arrayVersion.splice(index, 1);
        }
    document.getElementById('SearchActivity' + i).classList.remove("searchActiveActivity");
    }else{
        document.getElementById('SearchActivity' + i).classList.add("searchActiveActivity");
        arrayVersion.push(i);
    }
    
    console.log(arrayVersion);
    
    displaySearch();
}


$('#switchFilter').click(function(evt){
    
    evt.preventDefault;
    if(window.location.search == '?filter=ok'){
        window.location.replace('yoda.php');
    }else{
        window.location.replace('yoda.php?filter=ok');
    }
    
});

$('#activityFilter').click(function(evt){
    
    evt.preventDefault;
    if(window.location.search == '?filter=activity'){
        window.location.replace('yoda.php');
    }else{
        window.location.replace('yoda.php?filter=activity');
    }
    
});