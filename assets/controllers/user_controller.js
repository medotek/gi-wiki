import '../styles/controllers/user.scss';

var Isotope = require('isotope-layout');

require('isotope-cells-by-row');

// make Isotope a jQuery plugin
jQueryBridget('isotope', Isotope, $);

$(document).ready(function () {

    const $grid = $('.iudCharacters').isotope({
        // main isotope options
        itemSelector: '.user-character',
        // set layoutMode
        layoutMode: 'vertical'
    });
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
                beforeSend: function () {
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
            var isReadonly = $form.hasClass('is-readonly');
            $form.find('#uid').prop('disabled', isReadonly);
        } else {
            var $form = $(this).closest('form');
            $form.toggleClass('is-readonly is-editing');
            var isReadonly = $form.hasClass('is-readonly');
            $form.find('#uid').prop('disabled', isReadonly);
            return false;
        }
    })


    $('.js-edit').on('click', function () {
        $('.js-cancel').css({
                display: 'inline-block'
            }
        )
        var $form = $(this).closest('form');
        $form.toggleClass('is-readonly is-editing');
        var isReadonly = $form.hasClass('is-readonly');
        $form.find('#uid').prop('disabled', isReadonly);
    });

    $('.js-save').on('click', function () {
        $('.js-cancel').css({
                display: 'none'
            }
        )
    })

    $('.js-cancel').on('click', function () {
        $('.js-cancel').css({
                display: 'none'
            }
        )
        var $form = $(this).closest('form');
        $form.toggleClass('is-readonly is-editing');
        var isReadonly = $form.hasClass('is-readonly');
        $form.find('#uid').prop('disabled', isReadonly);
    });

    $('.gridFilter').click(function () {
        $('.horizontalFilter').removeClass("filterActive");
        $(this).addClass("filterActive");

        $('.user-profile .section-user-builds #uidProfile .iudCharacters .user-character').css({
            'display': 'block',
            'width': 'auto',
            'max-height': 'inherit',
            'min-height': 'inherit',
            'border-radius': '5px',
            'padding': '5px',
            'margin': '15px'
        });
        $('.character-constellations, .character-weapon, .character-artifacts, .sets-effect, .character-name').css({
            'display': 'none',
        });
        $grid.isotope({
            layoutMode: 'cellsByRow',
            itemSelector: '.user-character',
            masonry: {
                isFitWidth: true
            }
        })
    });


    $('.horizontalFilter').click(function () {
        $('.gridFilter').removeClass("filterActive");
        $(this).addClass("filterActive");

        const characterDatas = $('.iudCharacters.user-character');
        characterDatas.each(function () {
            console.log(characterDatas);
        })

        $('.character-constellations, .character-weapon, .character-artifacts, .sets-effect, .character-name').css({
            'display': '',
        });

        $('.user-profile .section-user-builds #uidProfile .iudCharacters .user-character').css({
            'display': '',
            'width': '',
            'max-height': '',
            'min-height': '',
            'border-radius': '',
            'padding': ''
        });

        $grid.isotope({
            layoutMode: 'vertical',
            itemSelector: '.user-character',
            masonry: {
                isFitWidth: true
            }
        })


    });

    $('#user-character > .jsCharacterData').each(function(index, item) {
        const characterData = $(item).data('characters');
        $(item).parent().on('mouseenter', function() {
            // console.log((characterData));
        });
    });

});

