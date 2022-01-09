$(document).ready(function () {

	"use strict";

	/* ================= sliders =================  */

	$(".service_slider").owlCarousel({
		autoplay: true,
		autoplayTimeout: 5000,
		loop: false,
		margin: 30,
		nav: true,
		pagination: false,
		dots: true,
		smartSpeed: 1000,
		responsiveClass: true,
		rtl: true,
		responsive: {
			0: {
				items: 3,
				margin: 10
			},
			992: {
				items: 3,
			}
		}
	});
	$("#up_Ads").owlCarousel({
		autoplay: true,
		autoplayTimeout: 5000,
		loop: false,
		margin: 30,
		nav: true,
		pagination: false,
		dots: false,
		smartSpeed: 1000,
		responsiveClass: true,
		rtl: true,
		responsive: {
			0: {
				items: 2,
			},
			575: {
				items: 2,
			},
			767: {
				items: 3,
			},
			1024: {
				items: 4
			},
			1300: {
				items: 6
			}
		}
	});
	$("#related_Ads").owlCarousel({
		autoplay: true,
		autoplayTimeout: 5000,
		loop: false,
		margin: 30,
		nav: true,
		pagination: false,
		dots: false,
		smartSpeed: 1000,
		responsiveClass: true,
		rtl: true,
		thumbs: false,
		responsive: {
			0: {
				items: 1,
			},
			575: {
				items: 2,
			},
			767: {
				items: 2,
			},
			1300: {
				items: 3
			}
		}
	});
	//========== ad slider ==============
	$("#ad_slider").owlCarousel({
		nav: true,
		dots: false,
		loop: true,
		items: 1,
		thumbs: true,
		thumbImage: true,
		rtl: true,
		thumbsPrerendered: true,
		thumbItemClass: 'owl-thumb-item',
		thumbContainerClass: 'owl-thumbs',
	});
	//========== ad popup slider ==============
	$("#imgPopup_slider").owlCarousel({
		nav: true,
		loop: true,
		dots: false,
		items: 1,
		rtl: true,
	});
	$('.multi-select').multiSelect();
});

/* =================  window load =================  */

$(window).on('load', function () {
	/*----- loader ---------*/
	$('.loader').fadeOut();

	/*----- WoW Animations ---------*/
	wow = new WOW();
	wow.init();

	/* after close modal */
	var myModalEl = $('#addModal');
	if (myModalEl.length) {
		myModalEl.on('hidden.bs.modal', function (event) {
			const URL = $('#_video').attr('src');
			$('#_video').attr('src', ' ');
			$('#_video').attr('src', URL)
		})
	}
});

/* =================  window Scroll =================  */

$(window).on('scroll , load', function () {
	var window_top = $(window).scrollTop();

	/*---------- go to top button ---------*/
	if (window_top > 600) {
		$('.goto_top').fadeIn();
	} else {
		$('.goto_top').fadeOut();
	}
});

$('.goto_top').on('click', function (e) {
	e.preventDefault();
	$('body , html').animate({
		scrollTop: 0
	}, 1000);
});
// ======= change files ===========
$('.file input').on('change', function (e) {
	files = e.target.files.length;
	$(this).parent().find('.numberOfImages').text(`${files} صور مختاره`).removeClass('d-none');
})
// ------ filter active -----------
$('.top_filter li').on('click', function (e) {
	e.preventDefault();
	$('.top_filter li').removeClass('active');
	$(this).addClass('active');
	$('.sub_fiter').removeClass('d-none');
	$('.reset').removeClass('d-none');
})
$('.sub_fiter li').on('click', function (e) {
	e.preventDefault();
	$('.sub_fiter li').removeClass('active');
	$(this).addClass('active');
	$('.sub_sub_fiter').removeClass('d-none');
})
$('.sub_sub_fiter li').on('click', function (e) {
	e.preventDefault();
	$('.sub_sub_fiter li').removeClass('active');
	$(this).addClass('active');
	$('.model').removeClass('d-none');
})
$('.reset').on('click', function (e) {
	e.preventDefault();
	$(this).addClass('d-none');
	$('.sub_fiter').addClass('d-none');
	$('.sub_sub_fiter').addClass('d-none');
	$('.model').addClass('d-none');
})
/* ---------- grid view ----------*/
$('.grid_view').on('click', function () {
	$('.products').toggleClass('list').toggleClass('grid');
	$('.grid_view img').each(function (e) {
		$(this).toggleClass('d-none');
	})
})
/* --------- select -----------*/
$('.select_style select').on('change', function (e) {
	const select_val = e.target.value;
	const id = $(this).attr('id')
	const label = $(this).parent().find('label');
	if (label) {
		label.text(select_val)
	}
});
/* ---- add to wish list ----- */
$(".add_to_wish").on('click', function (e) {
	e.preventDefault();
	$(this).toggleClass('added');
})
//============ categories popup =================
$('.cat_popup li').on('click', function () {
	$('.cat_popup li').removeClass('active');
	$(this).addClass('active');
	cat = $(this).attr('data')
	$("#cat1").val(`${cat}  /`).removeClass('d-none');
	$('.chooseDepart').text('تغيير القسم');
	$('.sub_cat_popup').removeClass('d-none');
});
$('.sub_cat_popup li').on('click', function () {
	$('.sub_cat_popup li').removeClass('active');
	$(this).addClass('active');
	cat = $(this).attr('data')
	$("#cat2").val(`${cat}  /`).removeClass('d-none');
});
// ========== image popup ============
$('#ad_slider img').on('click', function () {
	$('#openPopUp').click()
});
// ========== add new number ===========
$("#addNum").on('click', function (e) {
	e.preventDefault();
	$('#addNewNum').removeClass('d-none').addClass('d-flex');
	$(this).addClass('d-none');
})
// ========== image render ==============

$("#_imageRender").change(function (e) {
        var file = e.originalEvent.srcElement.files[0];
        var reader = new FileReader();
        reader.onloadend = function() {
             $('#userImag').attr('src' ,reader.result);
        }
        reader.readAsDataURL(file);
		$('#userImag').removeClass('d-none');
		$('.addPhoto .cam').addClass('d-none');
		$('.addPhoto label').text('تغيير الصورة')
})