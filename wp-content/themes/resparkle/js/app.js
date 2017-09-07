$.fn.reverse = [].reverse;

var scrollTop = 0;

var mobile = false;

var touch = "ontouchstart" in window;

$(document).ready(function() {

	$("form").each(function() {
		$(this)[0].reset();
	});
	
	$("input[data-reg='date']").datepicker({
		constrainInput: true,
		dateFormat: "dd/mm/yy",
		onSelect: function() { $(this).removeClass("default"); }
	}).attr("data-def", "DD/MM/YYYY");
	
	$("form").attr("novalidate", "").submit(validateForm);
	
	$("input[data-def], textarea[data-def]").focus(function() {
		var e = $(this);
		if(e.is(".default")) {
			e.removeClass("default").val("");
			if(e.is("[data-pas]")) e.attr("type", "password");
		}
	}).blur(function() {
		var e = $(this);
		if(e.val() === "") {
			e.addClass("default").val(e.attr("data-def"));
			if(e.is("[type='password']")) e.attr("type", "text").attr("data-pas", 1);
		}
	}).blur();
	
	$("select[data-def]").change(function() {
		var e = $(this);
		if(e.find("option").first().is(":selected")) {
			e.addClass("default");
		} else {
			e.removeClass("default");
		}
	}).each(function() {
		$(this).children().first().text($(this).attr("data-def"));
	}).change();
	
	$("[data-req='1']").each(function() {
		$("label[for='" + $(this).attr("id") + "']").addClass("mandatory");
	});

	$("[data-toggle]").change(function() {
		if($(this).prop("checked")) {
			$("._" + $(this).attr("data-toggle") + "_").show();
			$(".__" + $(this).attr("data-toggle") + "__").hide();
		} else {
			$("._" + $(this).attr("data-toggle") + "_").hide();
			$(".__" + $(this).attr("data-toggle") + "__").show();
		}
	}).change();

	$("select[data-def='Country']").change(function() {
		var state = $(this).parent().next();
		if($(this).val() == 'Australia') {
			state.find("select").show();
			state.find("input").hide();
		} else {
			state.find("select").hide();
			state.find("input").show();
		}
	}).change();

	$("span.obfuscated").each(function() {
		var span = $(this);
		var email = span.html();
		email = email.replace(/ \| <em>at<\/em> \| /i, "@");
		if(span.parent().is("a[href^='mailto:']")) {
			span.replaceWith(email);
		} else {
			span.replaceWith('<a href="mailto:' + email + '">' + email + "</a>");
		}
	});
	
	$("a[href^='mailto:']").each(function() {
		var href = $("<span>" + $(this).attr("href").substring(7) + "</span>");
		var span = href.find("span.obfuscated");
		var email = span.html();
		if(email) {
			email = email.replace(/ \| <em>at<\/em> \| /i, "@");
			span.replaceWith(email);
			$(this).attr("href", "mailto:" + href.text());
		}
	});

	$("h4.expand").click(function() {
		$(this).toggleClass("expanded");
	}).each(function() {
		$(this).nextUntil("h3, h4, hr").wrapAll("<div>");
	});

	$("#dismiss").click(function() {
		$("#alerts").addClass("closed");
		$.get(R + "/scripts/clearalerts");
	});

	$("a[data-overlay]").click(function() {
		var link = $(this);
		if(link.is(".active")) return false;
		$("a[data-overlay]").removeClass("active").filter("[data-overlay='" + link.attr("data-overlay") + "']").addClass("active");
		$("div[data-overlay]").hide().filter("[data-overlay='" + link.attr("data-overlay") + "']").show();
		$("#overlay").removeClass("closed").addClass("open");
		hideModal();
		showFader();
		$(window).resize();
		switch(link.attr("data-overlay")) {
			case "shop":
			$("#shop a:first-child").removeClass("open");
			break;
			case "search":
			$("#search input").focus();
			$("#overlay").scrollTop(0);
			break;
		}
		return false;
	});

	$("#close").click(function() {
		$("a[data-overlay]").removeClass("active");
		$("#overlay").removeClass("open").addClass("closed");
		hideFader();
	}).click();

	$("#overlay").click(function(e) {
		if(e.target == this) $("#close").click();
	})

	$("#filter").click(function() {
		$(this).toggleClass("expanded");
		$("#container").toggleClass("filtering");
		$(window).scroll();
	});

	$("#filters button").click(function() {
		$("#filter").click();
		$("html, body").animate({scrollTop: 0}, 400);
		return false;
	});

	/*
	$("#shop a, #categories a").click(function() {
		if(!tablet) return true;
		window.location = $(this).attr("href") + ($(this).attr("href").indexOf("?") > -1 ? "&" : "?") + "mf=" + _mf;
		return false;
	});

	if(!mf || mf == $.cookie("mf")) $("#filter").click();
	$.cookie("mf", _mf);
	*/

	$("#filters input").click(function() {
		var checkbox = $(this);
		var checkboxes = $("input[name='" + checkbox.attr("name") + "']");
		if(!checkbox.val()) checkboxes.prop("checked", false);
		checkboxes.filter("[value='']").prop("checked", !checkboxes.filter(":checked").length);
		$("#filters form").submit();
	});

	$("#filters a.clear").click(function() {
		$(this).parent().find("input[type='checkbox']").prop("checked", false);
		$("#filters form").submit();
	});

	$(document).on("click", "#sort a[data-sort]", function() {
		$("#filters input[name='sort']").val($(this).attr("data-sort"));
		$("#filters form").submit();
	});

	$(document).on("click", "#main div.product", function(e) {
		if($(e.target).is("a")) return true;
		window.location = $(this).children("a").attr("href");
		return false;
	});

	$("#back_to_top").click(function() {
		$("html, body").animate({scrollTop: 0}, 800);
	}).find("a").click(function(e) {
		e.stopImmediatePropagation();
	});

	if($("#filters").length) {

		$("#filters form").submit(function(e) {
			var url = $(this).serialize();
			window.location.hash = url ? "#" + url : "";
			e.preventDefault();
		});

		$(window).bind('hashchange', function() {
			loadResults(window.location.hash.substring(1));
		});

		if(window.location.hash) $(window).trigger('hashchange');
	}

	$("a.update").click(function() {
		$("#cart").submit();
	});

	$("a.checkout").click(function() {
		//FBQ("InitiateCheckout");
	});

	$("#cart input").keyup(function() {
		$("a.checkout").hide();
		$("a.update").css("display", "block");
	});

	$("#summary input").click(function() {
		$("#cart input[name='pickup']").val($(this).is(":checked") ? 1 : 0).keyup();
	});

	$(document).on("click", "a[data-modal]", function(e) {
		if(e.originalEvent && !$(this).is("[href^='javascript:']")) {
			showFader();
			$.get($(this).attr("href"), function() { $(e.target).click(); });
			return false;
		}
		showModal($(this).attr("data-modal"), $(this).attr("data-href"));
		return false;
	});

	$("#fader").click(function() {
		$("#overlay.open #close").click();
		$("#modal img.close").click();
	}).hide();

	if(!touch) {
		$(document).on("mouseenter", "[data-tooltip]", function() {
			$("#tooltip").text($(this).attr("data-tooltip")).stop().fadeIn(400);
		}).on("mouseleave", "[data-tooltip]", function() {
			$("#tooltip").stop().fadeOut(400);
		}).mousemove(function(e) {
			$("#tooltip").css("left", e.pageX).css("top", e.pageY);
		});
		$(document).on("mouseenter", ".swatch[data-img]", function() {
			var img = $(this).closest(".product").find("img").first();
			img.attr("data-init", img.attr("src")).attr("src", $(this).attr("data-img"));
		}).on("mouseleave", ".swatch[data-img]", function() {
			var img = $(this).closest(".product").find("img").first();
			img.attr("src", img.attr("data-init")).removeAttr("data-init");
		});
	}

	$("#masonry .item").click(function(e) {
		if($(e.target).is("a")) return true;
		var a = $(this).find("a.more");
		if(!a.length) a = $(this).find("a").first();
		if(a.is("[data-modal]")) {
			a.click();
		} else {
			if(a.attr("target") == "_blank") {
				window.open(a.attr("href"));
			} else {
				window.location = a.attr("href");
			}
		}
		return false;
	});

	$(document).on("click", "a.login", function() {
		$("a[data-overlay='login']").click();
		$("html, body").animate({scrollTop: 0}, 800);
		return false;
	});

	$("#shop a:first-child").click(function() {
		if(!mobile) return true;
		$(this).toggleClass("open");
		$(window).resize();
		return false;
	});

	$(window).resize(function() {
		mobile = $(this).width() < 768;
		tablet = $(this).width() < 992;
		$(".middle").height("auto").each(function() { $(this).width($(this).parent().width()).height($(this).closest(".row").height()); });
		var overlay = $("#overlay");
		overlay.height(overlay.children(".container").outerHeight());
		//if(!mobile || !$("body").is(".fader")) $("#frame").height($("#image").height());
		var modal = $("#modal");
		modal.css("top", ($(this).height() - modal.outerHeight()) / 2.25);
		modal.css("left", ($(this).width() - modal.outerWidth()) / 2);
		$("#video").height(Math.round($("#video").width() / 16 * 9));
		$("#alerts").height($("#alerts").height("auto").height());
		$(this).scroll();
	}).scroll(function() {
		var w = $(this);
		var images = $("img[data-src]:visible");
		if(w.scrollTop() < scrollTop) images.reverse();
		scrollTop = $(this).scrollTop();
		images.each(function() {
			var img = $(this);
			var offsetTop = img.offset().top;
			if(offsetTop < scrollTop + w.height() && offsetTop + img.height() > scrollTop) img.attr("src", img.attr("data-src")).removeAttr("data-src").one("error", function(e) {
				$(this).attr("src", "/images/products/220/default.png");
			});
		});
		var filters = $("#filters");
		if(filters.length) {
			if(tablet) {
				if(!filters.is(":visible") && scrollTop >= $("#results").offset().top) {
					$("#back_to_top").addClass("active");
				} else {
					$("#back_to_top").removeClass("active");
				}
			} else {
				if(scrollTop > filters.offset().top + filters.height()) {
					$("#back_to_top").addClass("active");
				} else {
					$("#back_to_top").removeClass("active");
				}
			}
		}
	}).resize();

	//FastClick.attach(document.body);

	initProduct();
});

$(window).load(function() {
	$(this).resize(function() {
		$(".masonry").each(function() {
			//$(this).height("auto").height(Math.ceil(($(this).outerHeight() + 20) / 240) * 240 - 30);
		});
	}).resize();
	$("#masonry").masonry({
		columnWidth: "#grid-sizer",
		itemSelector: ".col-xs-12"
	});
});

function validateForm(e) {
	
	var form = $(this);
	
	form.find(".required").removeClass("required");
	
	form.find("[data-req='1']:visible").each(function() {
		var e = $(this);
		switch(e[0].nodeName.toLowerCase()) {
			case "input":
				switch(e.attr("type")) {
					case "text":
					case "password":
					case "file":
						if(e.val() === "" || e.is(".default")) e.addClass("required");
					break;
					case "radio":
					case "checkbox":
						if(!$("input[name='" + e.attr("name") + "']:checked").length) e.addClass("required");
					break;
					case "hidden":
						if(e.val() === "") e.addClass("required");
					break;
				}
			break;
			case "textarea":
				if(e.val() === "" || e.is(".default")) e.addClass("required");
			break;
			case "select":
				if(e.val() === "") e.addClass("required");
			break;
		}
	});
	
	form.find("[data-reg]:visible").each(function() {
		var e = $(this);
		if(e.val() != "" && !e.is(".default")) {
			var pattern = "^";
			switch(e.attr("data-reg")) {
				case "integer":
					pattern += "[\\d ]*";
				break;
				case "float":
					pattern += "\\d*(\\.\\d*)?";
				break;
				case "date":
					pattern += "\\d{2}\\/\\d{2}\\/\\d{4}";
				break;
				case "email":
					pattern += "[\\w\\']+([\\.-][\\w\\']+)*@\\w+([\\.-]\\w+)*(\\.\\w{2,4})";
				break;
				default:
					pattern += e.attr("data-reg");
				break;
			}
			pattern += "$";
			var regex = new RegExp(pattern);
			if(!regex.test(e.val())) e.addClass("required");
		}
	});

	form.find("[data-mat]:visible").each(function() {
		var e = $(this);
		var _e = form.find("[name='" + e.attr("data-mat") + "']");
		if(e.val() != _e.val()) {
			e.addClass("required");
			_e.addClass("required");
		}
	});
	
	form.find(".required").each(function() {
		$("label[for='" + $(this).attr("id") + "']").addClass("required");
		$(this).parent("label").addClass("required");
	});
	
	if(form.find(".required").length) {
		//form.find(".required:not(label)").first().focus();
		var position = form.find(".required").first().offset().top;
		if(position < $(document).scrollTop() || position > $(document).scrollTop() + $(window).height()) {
			$("html, body").animate({scrollTop: position - 5}, 800, null, function() { form.find(".required:not(label)").first().focus(); });
		} else {
			form.find(".required:not(label)").first().focus();
		}
		e.stopImmediatePropagation();
		return false;
	}
	
	$(this).find(".default").val("");
	$(this).find("[type='submit']:not([name])").prop("disabled", true);
	return true;
}

function loadResults(url) {
	$.ajax({
		type: "GET",
		url: $("#filters form").attr("action") + "?" + url,
		success: function(data) {
			var page = $(data);
			$("#sort").html(page.find("#sort").html());
			$("#results").html(page.find("#results").html());
			$("#paging").html(page.find("#paging").html());
			$(window).scroll();
		},
		complete: function() {
			$("#filters").find("input").prop("disabled", false);
			$("#results").removeClass("loading");	
			var form = $("#filters form");
			form.find("input[type='checkbox']").prop("checked", false);
			form.deserialize(url);
			form.find("input[value='']").each(function() {
				if(!form.find("input[name='" + $(this).attr("name") + "']:checked").length) $(this).prop("checked", true);
			});
		}
	});
	$("#filters").find("input").prop("disabled", true);
	$("#results").addClass("loading");
}

function selectStock() {

	var size = $("input[name='size']:checked").val();
	var colour = $("input[name='colour']:checked").val();

	$("#details form input[type='radio']").prop("disabled", true).parent().addClass("disabled");

	var selected = null;
	var prices = [];
	var rrps = [];

	for(var i in stock) {
		var item = stock[i];
		if(size == item.size && colour == item.imageID) selected = item;
		if(item.stock < 1) continue;
		if(!size || size == item.size) $("input[name='colour'][value='" + item.imageID + "']").prop("disabled", false).parent().removeClass("disabled");
		if(!colour || colour == item.imageID) $("input[name='size'][value='" + item.size + "']").prop("disabled", false).parent().removeClass("disabled");
		if((!size || size == item.size) && (!colour || colour == item.imageID)) {
			prices.push(item.sale ? item.sale : item.price);
			rrps.push(item.price);
		}
	}

	if(selected) {
		$("#code").show().find("span").text(selected.code);
	} else {
		$("#code").hide();
	}

	$("input[name='stock']").val(selected ? selected.stockID : 0);
	//$("input[name='quantity'], #details button").prop("disabled", selected ? false : true);

	$("#price").removeClass("sale");
	$("#was").hide();

	if(prices.length) {
		var price = Math.min.apply(Math, prices);
		var rrp = Math.min.apply(Math, rrps);
		if(price < rrp) {
			$("#price").addClass("sale").text("$" + formatPrice(price));
			$("#was").show().find("span").text("$" + formatPrice(rrp));
		} else {
			$("#price").text("$" + formatPrice(price));
		}
	} else {
		$("#price").text("Out of stock");
	}
}

var scroll_top = null;

function showModal(handle, href, params, loaded) {

	if(!loaded) $("#fader").click();

	showFader();
	
	scroll_top = $(window).scrollTop();
	
	var modal = $("#modal");
	
	if(href) {
		modal.load(href, params, function(response) {
			if(response) {
				showModal(handle, null, null, true);
			} else {
				hideModal();
			}
		});
		return;
	} else {
		var source = $("textarea[data-modal='" + handle + "']");
		if(source.length) modal.html(source.text());
		modal.attr("class", handle);
	}
	
	modal.find(".close").click(hideModal);
	
	$("body").addClass("fader").attr("data-modal", handle);
	
	if(mobile) $("html, body").scrollTop(0);
	
	switch(handle) {
		case "quick_shop":
			modal.find("form").submit(validateForm);
			initProduct();
		break;
		case "size_guide":
			modal.find("a").click(function() {
				$(this).addClass("active").siblings().removeClass("active");
				$(window).resize();
			}).first().click();
		break;
		case "gallery":
			initGallery(10);
		break;
	}

	modal.find("img").one("load", function() {
		$(window).resize();
	});

	$(window).resize();
}

function hideModal() {
	$("body").removeClass("fader");
	if(mobile && scroll_top !== null) $("html, body").scrollTop(scroll_top);
	scroll_top = null;
	hideFader();
	$("#modal").empty();
	$(window).resize();
}

function showFader() {
	$("#fader").stop().fadeIn(200);
}

function hideFader() {
	$("#fader").stop().fadeOut(200, function() { $(this).hide(); });
}

function initProduct() {

	if($("#thumbnails").length) initGallery();

	$("#details [type='submit']").click(function() {
		var size = $("input[name='size']:checked").val();
		var colour = $("input[name='colour']:checked").val();
		if(size && colour) return true;
		if(!size) $("label[for='size'] span").text("Select a size").addClass("sale");
		if(!colour) $("label[for='colour'] span").text("Select a colour").addClass("sale");
		return false;
	});

	$("#details form").submit(function() {
		if(parseInt($("input[name='item']").val()) && !parseInt($("input[name='wishlist']").val())) return true;
		showFader();
		$.post($(this).attr("action"), $(this).serialize(), function(response) {
			showModal("add_to_bag", R + "/add-to-bag", {"id": parseInt(response)});
		});
		$("input[name='wishlist']").val(0);
		return false;
	}).each(selectStock).find("input[type='radio']").click(function(e) {
		var input = $(this);
		var label = $("label[for='" + input.attr("name") + "'] span").removeClass("sale");
		if(input.data("checked")) {
			input.prop("checked", false);
			label.text("");
		} else {
			label.text(input.next().text());
			if(input.is("[data-href]")) {
				$("#image").addClass("loading");
				$("<img>").one("load", function() {
					if($(e.target).is(":checked")) {
						$("#image").attr("src", $(this).attr("src")).attr("alt", $(e.target).attr("data-alt")).removeClass("loading");
						$(window).resize();
					}
				}).attr("src", input.attr("data-href"));
				$("#thumbnails a").removeClass("active");
				$("#frame .cycle").css("visibility", "visible");
				if(!touch) {
					$("#zoom").trigger("zoom.destroy");
					if(input.is("[data-zoom]")) {
						$("#zoom").zoom({url: input.attr("data-zoom")});
						$("#frame").addClass("zoom");
					} else {
						$("#frame").removeClass("zoom");
					}
				}
			}
		}
		$("input[type='radio'][name='" + input.attr("name") + "']").each(function() {
			$(this).data("checked", $(this).prop("checked"));
		});
		selectStock();
	}).each(function() {
		var input = $(this);
		if($("input[type='radio'][name='" + input.attr("name") + "']").length == 1) if(!input.is(":checked")) input.click();
	}).filter(":checked").click();

	$("#wishlist.wishlist").click(function() {
		$("input[name='wishlist']").val(1);
		$(this).prev().click();
		return false;
	});
}

function initGallery(thumbs) {

	var frame = $("#frame");
	var image = frame.find("#image");
	var video = frame.find("#video");
	var zoom = frame.find("#zoom");
	var caption = $("#caption");
	var thumbnails = $("#thumbnails");
	var scroller = thumbnails.find("#scroller");
	var holder = scroller.find("#holder");
	var images = thumbnails.find("a");

	if(!images.length) return;

	var width = $(images[0]).width() + 20;

	images.click(function(e) {
		image.addClass("loading");
		if($(this).is("[data-youtube]")) {
			image.hide();
			video.show();
			video.html('<iframe src="https://www.youtube.com/embed/' + $(this).attr("data-youtube") + '?rel=0" frameborder="0" allowfullscreen></iframe>');
		} else {
			image.show();
			video.hide().empty();
			$("<img>").one("load", function() {
				if($(e.target).is(".active")) {
					image.attr("src", $(this).attr("src")).attr("alt", $(e.target).attr("data-alt")).removeClass("loading");
					$(window).resize();
				}
			}).attr("src", $(this).attr("data-href"));
			frame.find(".cycle").css("visibility", images.length > 1 ? "visible" : "hidden");
		}
		caption.text($(this).attr("data-caption"));
		images.removeClass("active");
		$(this).addClass("active");
		if(!touch) {
			zoom.trigger("zoom.destroy");
			if($(this).is("[data-zoom]")) {
				zoom.zoom({url: $(this).attr("data-zoom")});
				frame.addClass("zoom");
			} else {
				frame.removeClass("zoom");
			}
		}
		return false;
	}).first().click();

	holder.width(images.length * width - 20);

	if(images.length > thumbs + 2) {
		scroller.before($("<div>").addClass("scroller"));
		scroller.after($("<div>").addClass("scroller"));
		var scroll = 0;
		var min_scroll = 0;
		var max_scroll = (images.length - thumbs) * width;
		scroller.siblings().click(function() {
			if($(this).is(":first-child")) {
				scroll -= width;
			} else {
				scroll += width;
			}
			if(scroll > max_scroll) scroll = max_scroll;
			if(scroll < min_scroll) scroll = min_scroll;
			holder.css("right", scroll);
		});
	}

	frame.find(".cycle").click(function() {
		var active = images.filter(".active");
		if($(this).is(".next")) {
			if(active.is(":last-child") || !active.length) {
				active = images.first();
			} else {
				active = active.next();
			}
		} else {
			if(active.is(":first-child") || !active.length) {
				active = images.last();
			} else {
				active = active.prev();
			}
		}
		active.click();
	});
}