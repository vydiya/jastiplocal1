/*!
 * main.js (fixed)
 * Pastikan file ini dijalankan setelah jQuery (dan setelah matchHeight jika kamu pakai plugin)
 */

(function ($) {
    "use strict";

    // Jika kamu memang memanggil $.noConflict() di tempat lain, jangan panggil lagi di sini.
    // Kita pakai IIFE dengan jQuery sebagai param sehingga $ pasti jQuery di dalam scope ini.

    $(function () {
        // Inisialisasi SelectFx (jika ada)
        try {
            [].slice.call(document.querySelectorAll('select.cs-select')).forEach(function (el) {
                new SelectFx(el);
            });
        } catch (e) {
            // jika SelectFx tidak tersedia, lewati tanpa crash
            // console.warn('SelectFx unavailable', e);
        }

        // Inisialisasi bootstrap-select (pastikan plugin terpasang)
        try {
            if ($.fn && typeof $.fn.selectpicker === 'function') {
                $('.selectpicker').selectpicker();
            } else {
                // jika tidak ada plugin, jangan error
                // console.warn('selectpicker plugin not available');
            }
        } catch (e) {
            // nothing
        }

        // search open/close
        $('.search-trigger').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).parent('.header-left').addClass('open');
        });

        $('.search-close').on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $('.search-trigger').parent('.header-left').removeClass('open');
        });

        // matchHeight guard (plugin optional)
        if ($.fn && typeof $.fn.matchHeight === 'function') {
            $('.card').matchHeight();
        }

        // Counter Number
        $('.count').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 3000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });

        // Menu Trigger
        $('#menuToggle').on('click', function (event) {
            var windowWidth = $(window).width();
            if (windowWidth < 1010) {
                $('body').removeClass('open');
                if (windowWidth < 760) {
                    $('#left-panel').slideToggle();
                } else {
                    $('#left-panel').toggleClass('open-menu');
                }
            } else {
                $('body').toggleClass('open');
                $('#left-panel').removeClass('open-menu');
            }
        });

        // submenu text
        $(".menu-item-has-children.dropdown").each(function () {
            $(this).on('click', function () {
                var $temp_text = $(this).children('.dropdown-toggle').html();
                $(this).children('.sub-menu').prepend('<li class="subtitle">' + $temp_text + '</li>');
            });
        });

        // Load / Resize 
        $(window).on("load resize", function () {
            var windowWidth = $(window).width();
            if (windowWidth < 1010) {
                $('body').addClass('small-device');
            } else {
                $('body').removeClass('small-device');
            }
        });

    }); // end $(function)

})(jQuery);
