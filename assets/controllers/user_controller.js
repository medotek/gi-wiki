import '../styles/controllers/user.scss';

var Isotope = require('isotope-layout');

require('isotope-cells-by-row');

// make Isotope a jQuery plugin
jQueryBridget('isotope', Isotope, $);

$(document).ready(function () {

    const $grid = $('.uidCharacters').isotope({
        // main isotope options
        itemSelector: '.user-character',
        // set layoutMode
        layoutMode: 'vertical',
        getSortData: {
            constellations: '[data-constellations] parseInt'
        },
    });

    var filterFns = {
        // show if number is greater than 50
        numberGreaterThan50: function () {
            var number = $(this).has('.number').text();
            return parseInt(number, 10) > 50;
        },
        // show if name ends with -ium
        ium: function () {
            var name = $(this).has('.name').text();
            return name.match(/ium$/);
        }
    };

    $('.element-filter').on('click', 'button', function () {
        var filterValue = $(this).attr('data-filter');
        // use filterFn if matches value
        filterValue = filterFns[filterValue] || filterValue;
        $grid.isotope({
            filter: filterValue,
        });
    }).each(function (i, buttonGroup) {
        var $buttonGroup = $(buttonGroup);
        $buttonGroup.on('click', 'button', function () {
            $buttonGroup.find('.active-filter').removeClass('active-filter');
            $(this).addClass('active-filter');
        });
    });

    $('#constellation-filter').click(function () {

        if (!$(this).hasClass('active-filter')) {
            $(this).addClass('active-filter');
            $grid.isotope({
                sortBy: 'constellations',
                sortAscending: false
            });
            if ($('.gridFilter').hasClass('filterActive')) {
                $('.character-constellations').css({
                    display: 'block',
                    textAlign: 'center'
                })
                $grid.isotope({
                    masonry: {
                        columnWidth: ''
                    }
                });
            }
        } else {
            $(this).removeClass('active-filter');
            $grid.isotope({
                sortBy: ''
            });

            if ($('.gridFilter').hasClass('filterActive')) {
                $('.character-constellations').css({
                    display: 'none',
                    textAlign: ''
                })

                $grid.isotope({
                    masonry: {
                        columnWidth: ''
                    }
                });
            }
        }
    })

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
            $form.has('#uid').prop('disabled', isReadonly);
        } else {
            var $form = $(this).closest('form');
            $form.toggleClass('is-readonly is-editing');
            var isReadonly = $form.hasClass('is-readonly');
            $form.has('#uid').prop('disabled', isReadonly);
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
        $form.has('#uid').prop('disabled', isReadonly);
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
        $form.has('#uid').prop('disabled', isReadonly);
    });

    $('.gridFilter').click(function () {
        $('.horizontalFilter').removeClass("filterActive");
        $(this).addClass("filterActive");
        $grid.isotope({filter: '*'});
        $('.user-profile .section-user-builds #uidProfile .uidCharacters .user-character').css({
            'display': 'block',
            'width': 'auto',
            'max-height': 'inherit',
            'min-height': 'inherit',
            'border-radius': '5px',
            'padding': '5px',
            'margin': '15px',
            'height': 'auto',

        });

        $('.user-profile .section-user-builds #uidProfile .uidCharacters .user-character .character-image .character-icon').css({
            'width': 80,
            'height': 80,
            'border-top-left-radius': 5,
            'border-top-right-radius': 5,
        })

        $('.character-constellations, .character-weapon, .character-artifacts, .sets-effect, .character-name').css({
            'display': 'none',
        });

        $('.uidCharacters > .user-character').addClass('cells');
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
        $grid.isotope({filter: '*'});
        // // const characterDatas = $('.iudCharacters.user-character');
        // characterDatas.each(function () {
        //     // console.log(characterDatas);
        // })

        $('.character-constellations, .character-weapon, .character-artifacts, .sets-effect, .character-name').css({
            'display': '',
        });

        $('.user-profile .section-user-builds #uidProfile .uidCharacters .user-character').css({
            'display': '',
            'width': '',
            'max-height': '',
            'min-height': '',
            'border-radius': '',
            'padding': ''
        });

        $('.uidCharacters > .user-character').removeClass('cells');

        $grid.isotope({
            layoutMode: 'vertical',
            itemSelector: '.user-character',
            masonry: {
                isFitWidth: true
            }
        })


    });

    $('.uidCharacters').each(function (index, item) {
        var $character = $(item);
        $character.on('click', '.user-character', function () {
            $character.find('.active-character').removeClass('active-character');
            $(this).addClass('active-character');
        });
    });

    $('#user-character > .jsCharacterData').each(function (index, item) {
        // const characterData = $(item).data('characters')

        $(item).parent().children('#characterCardJs').css({
            "display": ""
        })


        // setTimeout(function () {
        //     $(item).parent().on('mouseenter', function () {
        //         if ($('.gridFilter').hasClass('filterActive')) {
        //             // console.log((characterData));
        //
        //             setTimeout(function () {
        //                 $(item).parent().children('#characterCardJs').css({
        //                     "opacity": "0",
        //                     "display": "block",
        //                 }).show().animate({opacity: 1}, 100)
        //             }, 500);
        //         }
        //     }).on('mouseleave', function () {
        //         if ($('.gridFilter').hasClass('filterActive')) {
        //
        //             setTimeout(function () {
        //                 $(item).parent().children('#characterCardJs').css({
        //                     "opacity": "1",
        //                     "display": "none"
        //                 }).hide().animate({opacity: 0}, 100)
        //             }, 500);
        //         }
        //     });
        // }, 200);


        // $(item).parent()
        //     .on('mouseenter', function () {
        //     $(this).addClass('campaign-hover');
        //     updateHover();
        // }).on('mouseleave', function () {
        //     $('.campaign-hover').removeClass('campaign-hover');
        //     updateHover();
        // })

        // var offset = $("#characterCardJs").offset();
        // var posY = offset.top - $(window).scrollTop();
        // var posX = offset.left - $(window).scrollLeft();

        function updateHover() {
            if ($(item).parent().hasClass('campaign-hover') && $('.gridFilter').hasClass('filterActive')) {
                $(item).parent().children('#characterCardJs').css({
                    "opacity": "0",
                    "display": "block",
                }).show().animate({opacity: 1}, 100)
            } else {
                $(item).parent().children('#characterCardJs').css({
                    "opacity": "1",
                    "display": "none"
                }).hide().animate({opacity: 0}, 100)


            }


        }

    });
})

