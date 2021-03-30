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

    /* AJAX */

    $("#uid-form").on("submit", function(e){
        e.preventDefault();
        let data = {};
        $(this).serializeArray().forEach((object)=>{
            if (object.value === '') {
                object = null;
            } else {
            data[object.name] = object.value;
            }
        });

        // data = JSON.stringify(data);

        $.ajax({
            type: 'post',
            url: '/account/profile/set/uid',
            dataType: 'json',
            data : JSON.stringify(data),
            success: function (data) {
                console.log(data)
            },
            error: function(data) {
                console.log(data.responseJSON)
            }
        });

        console.log(data);

    })
});
