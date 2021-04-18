import '../styles/controllers/user.scss';

$(document).ready(function () {

    /*AJAX LOADER*/

    $("#user-build-remove").click(function () {
        if (confirm("Etes vous sûr de supprimer ce build?")) {
            location.href = $("#user-build-remove").data("deleteBuildLink");
        } else {
            return false;
        }
    });

    /* AJAX */

    $("#uid-form").on("submit", function (e) {
        e.preventDefault();

        if (confirm("Voulez-vous ajouter ou mettre à jour votre uid?")) {
            let data = {};
            $(this).serializeArray().forEach((object) => {
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
                data: JSON.stringify(data),
                beforeSend: function() {
                    $("#wait").show();
                },
                success: function (data) {
                    $("#wait").hide();
                    $(".page").append('<div class="ajax-succes">Votre uid a bien été ajouté/mis à jour</div>')
                },
                error: function (data) {
                    $("#wait").hide();
                    window.alert(data.responseJSON);
                }
            });


            var $form = $(this).closest('form');
            $form.toggleClass('is-readonly is-editing');
            var isReadonly  = $form.hasClass('is-readonly');
            $form.find('#uid').prop('disabled', isReadonly);
        } else {
            var $form = $(this).closest('form');
            $form.toggleClass('is-readonly is-editing');
            var isReadonly  = $form.hasClass('is-readonly');
            $form.find('#uid').prop('disabled', isReadonly);
            return false;
        }
    })


    $('.js-edit').on('click', function() {
        $('.js-cancel').css({
                display: 'inline-block'
            }
        )
        var $form = $(this).closest('form');
        $form.toggleClass('is-readonly is-editing');
        var isReadonly  = $form.hasClass('is-readonly');
        $form.find('#uid').prop('disabled', isReadonly);
    });

    $('.js-save').on('click', function() {
        $('.js-cancel').css({
                display: 'none'
            }
        )
    })

    $('.js-cancel').on('click', function() {
        $('.js-cancel').css({
                display: 'none'
            }
        )
        var $form = $(this).closest('form');
        $form.toggleClass('is-readonly is-editing');
        var isReadonly  = $form.hasClass('is-readonly');
        $form.find('#uid').prop('disabled', isReadonly);
    });


});
