///////////////-----------pagination-------------////
$(document).ready(function() {
    $('#developers').pageMe({
        pagerSelector: '#developer_page',
        showPrevNext: true,
        hidePageNumbers: false,
        perPage: 7
    });
});
///////////////-----------Fin pagination-------------////

////////////----pour graphique accueil----////////
var nom_page = window.location.pathname;
if (nom_page.includes("accueil.php")) {
    var divDiag = document.getElementById("chartdiv");
    if (divDiag) {
        divDiag.addEventListener("click", sijeDoubleClikDev);
        divDiag.addEventListener("click", sijeDoubleClikRef);
        divDiag.addEventListener("click", sijeDoubleClikData);
    }

    function sijeDoubleClikDev() {
        ///////////////////////----dev---///////////////////////////
        var diagramdevAbs = document.getElementById("id-455");
        if (diagramdevAbs) { diagramdevAbs.addEventListener("click", devAbsclicker); }
        var jour = document.getElementById("jourR");
        var jourR = jour.getAttribute("class");
        var promo_dev = document.getElementById("nom_promo1");
        var dev = promo_dev.getAttribute("class");

        function devAbsclicker() {
            document.location.href = "presence.php?Ref=" + dev + "&statut=absents&laDate=" + jourR;

        }

        var diagramdevPres = document.getElementById("id-413");
        if (diagramdevPres) { diagramdevPres.addEventListener("click", devPresclicker); }

        function devPresclicker() {
            document.location.href = "presence.php?Ref=" + dev + "&statut=present&laDate=" + jourR;

        }
        ///////////////////////----Fin dev---///////////////////////////
    }

    function sijeDoubleClikRef() {
        ///////////////////////----Ref Dig---///////////////////////////
        var diagramrefAbs = document.getElementById("id-469");
        if (diagramrefAbs) { diagramrefAbs.addEventListener("click", refAbsclicker); }
        var jour = document.getElementById("jourR");
        var jourR = jour.getAttribute("class");
        var promo_ref = document.getElementById("nom_promo2");
        var ref = promo_ref.getAttribute("class");

        function refAbsclicker() {
            document.location.href = "presence.php?Ref=" + ref + "&statut=absents&laDate=" + jourR;
        }

        var diagramrefPres = document.getElementById("id-427");
        if (diagramrefPres) { diagramrefPres.addEventListener("click", refPresclicker); }

        function refPresclicker() {
            document.location.href = "presence.php?Ref=" + ref + "&statut=present&laDate=" + jourR;

        }
        ///////////////////////----Fin Ref Dig---///////////////////////////
    }

    function sijeDoubleClikData() {
        ///////////////////////----data art---///////////////////////////
        var diagramdataAbs = document.getElementById("id-483");
        if (diagramdataAbs) { diagramdataAbs.addEventListener("click", dataAbsclicker); }
        var jour = document.getElementById("jourR");
        var jourR = jour.getAttribute("class");
        var promo_dat = document.getElementById("nom_promo2");
        var datArt = promo_dat.getAttribute("class");

        function dataAbsclicker() {
            document.location.href = "presence.php?Ref=" + datArt + "&statut=absents&laDate=" + jourR;

        }

        var diagramdataPres = document.querySelector("#id-441");
        diagramdataPres.addEventListener("click", dataPresclicker);

        function dataPresclicker() {
            document.location.href = "presence.php?Ref=" + datArt + "&statut=present&laDate=" + jourR;

        }
        ///////////////////////----Fin data art---///////////////////////////
    }
}
////////////----Fin pour graphique accueil----////////

/////////////////////////////----Pour la page paramettre----//////
var nom_page = window.location.pathname;
if (nom_page.includes("parametres.php")) {
    var nom = document.getElementById("nom_ag");

    var tel = document.getElementById("tel_ag");

    var login = document.getElementById("login_ag");

    var MDP = document.getElementById("mdp_ag");

    var ConfMDP = document.getElementById("confMdp_ag");


    function verification(e) { //verification des deux mots de passe lors de l inscription
        if (MDP) {
            if (MDP.value != ConfMDP.value) {
                ConfMDP.style.backgroundColor = "rgba(255, 0, 0, 0.5)"; //l equivalent de la classe rougMoins
                ConfMDP.value = "";
                ConfMDP.setAttribute("placeholder", "MOT DE PASSE DIFFERENT");
                e.preventDefault();
            }
        }
        if (nom.value == "" || tel.value == "" || login.value == "" || MDP.value == "" || ConfMDP.value == "") {
            if (nom.value == "") {
                nom.style.backgroundColor = "rgba(255, 0, 0, 0.5)"; //l equivalent de la classe rougMoins
                nom.setAttribute("placeholder", "Remplir le nom de l'agent");
            }
            if (tel.value == "") {
                tel.style.backgroundColor = "rgba(255, 0, 0, 0.5)"; //l equivalent de la classe rougMoins
                tel.setAttribute("placeholder", "Remplir le numéro de téléphone de l'agent");
            }
            if (login.value == "") {
                login.style.backgroundColor = "rgba(255, 0, 0, 0.5)"; //l equivalent de la classe rougMoins
                login.setAttribute("placeholder", "Remplir le login de l'agent");
            }
            if (MDP.value == "") {
                MDP.style.backgroundColor = "rgba(255, 0, 0, 0.5)"; //l equivalent de la classe rougMoins
                MDP.setAttribute("placeholder", "Remplir le mot de passe de l'agent");
            }
            if (ConfMDP.value == "") {
                ConfMDP.style.backgroundColor = "rgba(255, 0, 0, 0.5)"; //l equivalent de la classe rougMoins
                ConfMDP.setAttribute("placeholder", "Confirmez le mot de passe de l'agent");
            }
            e.preventDefault();
        }
    }
    /////////////////////////////----pour la page paramettre lors de l ajout d un agent----//////
    var valider_ajout_ag = document.getElementById("valider_ajout_ag");

    if (valider_ajout_ag) { valider_ajout_ag.addEventListener("click", verification); } //car parfois le bouton est cacher
    /////////////////////////////----Fin pour la page paramettre lors de l ajout d un agent----//////

    //////////////////////////////----Pour la page paramettre lors de la modification d un agent----/////////
    var valider_modif_ag = document.getElementById("valider_modif_ag");

    if (valider_modif_ag) { valider_modif_ag.addEventListener("click", verification); }

    /////////////////////////////----Fin pour la page paramettre lors de la modification d un agent----//////


    /////////////////////////////----modif admin ----//////
    var modif_admin = document.getElementById("valider_modif_adm");
    if (modif_admin) { modif_admin.addEventListener("click", verifmodif_admin); }

    function verifmodif_admin(e) {
        if (MDP.value != "") {
            if (MDP.value != ConfMDP.value) {
                ConfMDP.style.backgroundColor = "rgba(255, 0, 0, 0.5)"; //l equivalent de la classe rougMoins
                ConfMDP.value = "";
                ConfMDP.setAttribute("placeholder", "MOT DE PASSE DIFFERENT");
                e.preventDefault();
            }
        }
    }
    /////////////////////////////----Fin modif admin----//////

}
/////////////////////////////----Fin pour la page paramettre ----//////

/////////////////////////////----Fin pour exporter fichier ----////////////
var nom_page = window.location.pathname;
if (nom_page.includes("exportation.php")) {
    ////////////////////---Emargement---//////////////////
    var lab_dd_em = document.getElementById("lab_dd_em");
    var lab_df_em = document.getElementById("lab_df_em");
    var dd_em = document.getElementById("dd_em");
    var df_em = document.getElementById("df_em");
    var pdf_em = document.getElementById("pdf_em");

    pdf_em.addEventListener("click", verif_date_em);

    function verif_date_em(e) {
        lab_dd_em.textContent = "Date début";
        lab_df_em.textContent = "Date fin";
        lab_dd_em.style.color = lab_df_em.style.color = "black";
        dd_em.style.backgroundColor = df_em.style.backgroundColor = "white";


        if (df_em.value != "" && dd_em.value != "" && df_em.value < dd_em.value) {
            lab_dd_em.textContent = "Revoir la dat";
            lab_dd_em.style.color = "rgba(255, 0, 0, 0.5)";
            dd_em.style.backgroundColor = "rgba(255, 0, 0, 0.5)";
            e.preventDefault();
        }
        var auj = new Date();
        var date_dd_em = new Date(dd_em.value);
        if (date_dd_em > auj) {
            lab_dd_em.textContent = "Revoir la date";
            lab_dd_em.style.color = "rgba(255, 0, 0, 0.5)";
            dd_em.style.backgroundColor = "rgba(255, 0, 0, 0.5)";
            e.preventDefault();
        }
        var date_df_em = new Date(df_em.value);
        if (date_df_em > auj) {
            lab_df_em.textContent = "Revoir la date";
            lab_df_em.style.color = "rgba(255, 0, 0, 0.5)";
            df_em.style.backgroundColor = "rgba(255, 0, 0, 0.5)";
            e.preventDefault();
        }
    }
    ////////////////////---Fin Emargement---//////////////////

    ////////////////////---visiteur---//////////////////
    var lab_dd_vi = document.getElementById("lab_dd_vi");
    var lab_df_vi = document.getElementById("lab_df_vi");
    var dd_vi = document.getElementById("dd_vi");
    var df_vi = document.getElementById("df_vi");
    var pdf_vi = document.getElementById("pdf_vi");

    pdf_vi.addEventListener("click", verif_date_vi);

    function verif_date_vi(e) {
        lab_dd_vi.textContent = "Date début";
        lab_df_vi.textContent = "Date fin";
        lab_dd_vi.style.color = lab_df_vi.style.color = "black";
        dd_vi.style.backgroundColor = df_vi.style.backgroundColor = "white";


        if (df_vi.value != "" && dd_vi.value != "" && df_vi.value < dd_vi.value) {
            lab_dd_vi.textContent = "Revoir la dat";
            lab_dd_vi.style.color = "rgba(255, 0, 0, 0.5)";
            dd_vi.style.backgroundColor = "rgba(255, 0, 0, 0.5)";
            e.preventDefault();
        }
        var auj = new Date();
        var date_dd_vi = new Date(dd_vi.value);
        if (date_dd_vi > auj) {
            lab_dd_vi.textContent = "Revoir la date";
            lab_dd_vi.style.color = "rgba(255, 0, 0, 0.5)";
            dd_vi.style.backgroundColor = "rgba(255, 0, 0, 0.5)";
            e.preventDefault();
        }
        var date_df_vi = new Date(df_vi.value);
        if (date_df_vi > auj) {
            lab_df_vi.textContent = "Revoir la date";
            lab_df_vi.style.color = "rgba(255, 0, 0, 0.5)";
            df_vi.style.backgroundColor = "rgba(255, 0, 0, 0.5)";
            e.preventDefault();
        }
    }
    ////////////////////---Fin visiteur---//////////////////
}
/////////////////////////////----Fin pour exporter fichier ----////////////