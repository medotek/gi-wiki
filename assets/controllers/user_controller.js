import '../styles/controllers/user.scss';

$(document).ready(function () {

    $("#user-build-remove").click(function () {
        if(confirm("Etes vous sûr de supprimer ce build?")){
           location.href = $("#user-build-remove").data("deleteBuildLink");
        }
        else{
            return false;
        }
    });
});
