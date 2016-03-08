$(document).ready(function () {

    $(document).on("submit","form",function (e) {
        e.preventDefault();
        var vars = $(this).serializeJSON();
        var output = "";
        var msg = "No Infos Given";
        var user = "Name";
        $.each(vars,function (key,value) {
            if (key == "additional") {
                msg = value + "\r\n----------------\r\n";    
            } else if (key == "user") {
                user = value + "\r\n\r\n\r\n\r\n";    
            } else if (key != "additional" || key != "user") {
                output += "Group " + key + " --> " + value + "\r\n\r\n";
            }
        });
        output     = user + msg + output;
        output     = encodeURIComponent(output);
        if (output == "") {
            return alert("please select one case atleast");
        }
        mail = window.open('mailto:sayhello@hvlmnns.de?subject=Caramel&body=' + output);
        if (window.closeTimer) clearTimeout(closeTimer);
        window.closeTimer = window.setTimeout(function () {
            mail.close();  
        },50);
    });
});