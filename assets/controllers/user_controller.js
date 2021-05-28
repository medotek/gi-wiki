import '../styles/controllers/user.scss';
import 'animate.css';

const Isotope = require('isotope-layout');

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
            constellations: '[data-constellations] parseInt',
            level: '[data-level] parseInt',
            fetter: '[data-fetter] parseInt',
        },
    });

    const $characterInfoIsotope = $('#character-info-isotope')

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

    $('#level-filter').click(function () {

        if (!$(this).hasClass('active-filter')) {
            $(this).addClass('active-filter');
            $grid.isotope({
                sortBy: 'level',
                sortAscending: false
            });
            if ($('.gridFilter').hasClass('filterActive')) {
                $('.character-level').css({
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
                $('.character-level').css({
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

    $('#fetter-filter').click(function () {

        if (!$(this).hasClass('active-filter')) {
            $(this).addClass('active-filter');
            $grid.isotope({
                sortBy: 'fetter',
                sortAscending: false
            });
            if ($('.gridFilter').hasClass('filterActive')) {
                $('.character-fetter').css({
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
                $('.character-fetter').css({
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
            $('#uid').prop('disabled', isReadonly);
        } else {
            var $form = $(this).closest('form');
            $form.toggleClass('is-readonly is-editing');
            var isReadonly = $form.hasClass('is-readonly');
            $('#uid').prop('disabled', isReadonly);
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
        $('#uid').prop('disabled', isReadonly);
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
        $('#uid').prop('disabled', isReadonly);
    });

    $('.gridFilter').click(function () {
        $('.horizontalFilter').removeClass("filterActive");

        $(this).addClass("filterActive");
        $grid.isotope({filter: '*'});

        $('.element-filter').each(function (i, buttonGroup) {
            var $buttonGroup = $(buttonGroup);
            $buttonGroup.find('.active-filter').removeClass('active-filter');
        }).find('button:first-child').addClass('active-filter');

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

        $('#constellation-filter').removeClass('active-filter')
    });


    $('.horizontalFilter').click(function () {
        $('.gridFilter').removeClass("filterActive");
        $(this).addClass("filterActive");
        $('.uidCharacters > .user-character').removeClass('active-character')
        $('.element-filter').each(function (i, buttonGroup) {
            var $buttonGroup = $(buttonGroup);
            $buttonGroup.find('.active-filter').removeClass('active-filter');
        }).find('button:first-child').addClass('active-filter');
        $grid.isotope({filter: '*'});
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
                isFitWidth: true,
                columnWidth: ''
            }
        })
        $('#character-info-isotope').css({
            display: 'none',
        });

        $('.uidCharacters').css({
            width: ''
        })

    });


    var stickyInfoTop = $('#character-info-isotope').offset().top;


    var stickyCharacterInfo = function () {
        if ($('.gridFilter').hasClass('filterActive')) {
            var scrollTop = $(window).scrollTop();

            if (scrollTop > stickyInfoTop) {
                $('#character-info-isotope').css({
                    position: 'fixed',
                    top: '10px',
                    transform: 'inherit',
                    width: $('#uidProfile').width() * 0.4,
                    right: ($(window).width() - ($('#uidProfile').offset().left + $('#uidProfile').outerWidth()))
                }).addClass('sticky');
            } else {
                $('#character-info-isotope').css({
                    width: '',
                    position: '',
                    right: '',
                    top: '',
                    transform: '',
                    height: ''
                }).removeClass('sticky');
            }
        }
    };


    $('.uidCharacters').each(function (index, item) {
        var $character = $(item);
        $character.on('click', '.user-character', function () {
            if ($('.gridFilter').hasClass('filterActive')) {
                var $characterId = $(this).find('.jsCharacterData #characterDataId').data('characters-id');
                var $characterName = $(this).find('.jsCharacterData #characterDataName').data('characters-name');
                var $characterImage = $(this).find('.jsCharacterData #characterDataImage').data('characters-image');
                var $characterFetter = $(this).find('.jsCharacterData #characterDataFetter').data('characters-fetter');
                var $characterLevel = $(this).find('.jsCharacterData #characterDataLevel').data('characters-level');
                var $characterRarity = $(this).find('.jsCharacterData #characterDataRarity').data('characters-rarity');
                var $characterReliquaries = $(this).find('.jsCharacterData #characterDataReliquaries').data('characters-reliquaries');
                var $characterConstellations = $(this).find('.jsCharacterData #characterDataConstellations').data('characters-constellations');
                var $characterExtra = $(this).find('.jsCharacterData #characterDataExtra').data('characters-extra');

                var $imageSelector = $('#character-info-isotope > .character-info-grid > #character-info-image > img')
                var $detailsSelector = $('#character-info-details')
                if ($(this).has('.active-character')) {
                        // animate__fadeInLeft
                    var cImageWidthFunc = function () {
                        const $giContainerOuterWidth = $('.gi-container').outerWidth(true);
                        const $giContainerPaddingRightWidth = ($giContainerOuterWidth - $('.gi-container').innerWidth()) / 2
                        const $cImageWidth = $('#character-info-isotope').width() + $giContainerPaddingRightWidth
                        $imageSelector.attr('src', $characterImage).css({
                            width: $cImageWidth,
                            height: $('#character-info-isotope').height()
                        })
                    }

                    $detailsSelector.addClass('animate__animated animate__fadeInLeft').css({
                        display:'block',
                        position: 'absolute',
                        top: 20,
                        left: 20,
                    }).on('animationend', function() {
                        console.log("genshin animate end")
                    });

                    cImageWidthFunc()
                    $(this).on('resize load', function () {
                        cImageWidthFunc()
                    })


                    $('#character-info-isotope > .character-info-grid > #character-info-image >  #img-loading').css('display', 'block')
                    $('#character-info-isotope > .character-info-grid > #character-info-image > #cImage').removeClass('animate__animated animate__fadeIn').css('display', 'none')
                    $imageSelector.on('load', function () {
                        $('#character-info-isotope > .character-info-grid > #character-info-image > #img-loading').css('display', '')
                        $('#character-info-isotope > .character-info-grid > #character-info-image > #cImage').addClass('animate__animated animate__fadeIn').css('display', '')
                        $('#character-info-isotope > .character-info-grid > #character-info-details > #name').text($characterName)
                        $('#character-info-isotope > .character-info-grid > #character-info-details > #level').text('Niveau ' + $characterLevel)
                        $('#character-info-isotope > .character-info-grid > #character-info-details > #fetter').text('Affinité ' + $characterFetter)
                    });

                    $('#character-info-isotope').css({
                        display: 'block'
                    })

                    $('.uidCharacters').css({
                        width: '60%'
                    })
                    $grid.isotope({
                        masonry: {
                            columnWidth: ''
                        }
                    })


                    var stickyInfoTop = $('#character-info-isotope').offset().top;
                    var stickyCharacterInfo = function () {
                        var scrollTop = $(window).scrollTop();
                        if (scrollTop > stickyInfoTop) {
                            $('#character-info-isotope').css({
                                position: 'fixed',
                                top: '10px',
                                transform: 'inherit',
                                width: $('#uidProfile').width() * 0.4,
                                right: ($(window).width() - ($('#uidProfile').offset().left + $('#uidProfile').outerWidth()))
                            }).addClass('sticky');
                            console.log('scrollTop: ' + scrollTop)
                            console.log('stickyInfoTop : ' + stickyInfoTop)
                        } else {
                            $('#character-info-isotope').css({
                                width: '',
                                position: '',
                                right: '',
                                top: '',
                                transform: '',
                                height: ''
                            }).removeClass('sticky');
                        }
                    };

                    $(window).on('scroll resize load', function () {
                        stickyCharacterInfo();
                    });
                    $character.find('.active-character').removeClass('active-character');
                    $(this).addClass('active-character');
                    stickyCharacterInfo();


                    $(window).on('scroll', function () {
                        stickyCharacterInfo();

                        var $el1 = $('.uidCharacters'),
                            scrollTop1 = $(this).scrollTop(),
                            scrollBot1 = scrollTop1 + $(this).height(),
                            elTop1 = $el1.offset().top,
                            elBottom1 = elTop1 + $el1.outerHeight(),
                            visibleTop1 = elTop1 < scrollTop1 ? scrollTop1 : elTop1,
                            visibleBottom1 = elBottom1 > scrollBot1 ? scrollBot1 : elBottom1;
                        var yikes1 = visibleBottom1 - visibleTop1

                        if ($('#character-info-isotope').hasClass('sticky')) {
                            if (yikes1 < $('#character-info-isotope').height()) {
                                $('#character-info-isotope').css({
                                    width: '',
                                    position: '',
                                    right: '',
                                    top: 'inherit',
                                    bottom: 0,
                                    transform: 'none',
                                    height: ''
                                });
                            }
                        }
                    });
                } else {
                    $detailsSelector.removeClass('animate__animated animate__fadeInLeft').css({
                        display:'',
                        position: '',
                        top: '',
                        left: '',
                    })
                }
            }
        })

            // Remove active class on re-click
            .on('click', '.active-character', function () {
                $(this).removeClass('active-character');
                $('.uidCharacters').css({
                    width: ''
                })
                stickyCharacterInfo();


                $grid.isotope({
                    masonry: {
                        columnWidth: ''
                    }
                })
                $('#character-info-isotope').css({
                    display: 'none'
                })

            });


    });


    $('#user-character > .jsCharacterData').each(function (index, item) {
        // const characterData = $(item).data('characters')

        $(item).parent().children('#characterCardJs').css({
            "display": ""
        })

    });


})

