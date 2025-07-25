document.addEventListener("DOMContentLoaded", function () {
	document.querySelectorAll(".dropdown-menu a.dropdown-toggle").forEach((e) => {
		e.addEventListener("click", function (e) {
			const t = this.nextElementSibling;
			if (!t.classList.contains("show")) {
				this.closest(".dropdown-menu")
					.querySelectorAll(".show")
					.forEach((e) => {
						e.classList.remove("show");
					});
			}
			t.classList.toggle("show");
			const o = this.closest("li.nav-item.dropdown.show");
			o &&
				o.addEventListener("hidden.bs.dropdown", function () {
					document.querySelectorAll(".dropdown-submenu .show").forEach((e) => {
						e.classList.remove("show");
					});
				}),
				e.preventDefault();
		});
	});
	const e = document.getElementById("nav-toggle");
	e &&
		e.addEventListener("click", function (e) {
			e.preventDefault(), document.getElementById("main-wrapper").classList.toggle("toggled");
		});
	document.querySelectorAll("[data-bs-toggle='tooltip']").forEach((e) => {
		new bootstrap.Tooltip(e);
	});
	document.querySelectorAll("[data-bs-toggle='popover']").forEach((e) => {
		new bootstrap.Popover(e);
	});
	document.querySelectorAll("[data-bs-spy='scroll']").forEach((e) => {
		const t = bootstrap.ScrollSpy.getInstance(e);
		t && t.refresh();
	});
	document.querySelectorAll(".toast").forEach((e) => {
		new bootstrap.Toast(e);
	});
	document.querySelectorAll(".offcanvas").forEach((e) => {
		new bootstrap.Offcanvas(e);
	});
	const t = document.getElementById("editor");
	if (t) {
		new Quill(t, { modules: { toolbar: [[{ header: [1, 2, !1] }], [{ font: [] }], ["bold", "italic", "underline", "strike"], [{ size: ["small", !1, "large", "huge"] }], [{ list: "ordered" }, { list: "bullet" }], [{ color: [], background: [], align: [] }], ["link", "image", "code-block", "video"]] }, theme: "snow" });
	}
	const n = document.querySelectorAll(".timepickr");
	n.length &&
		n.forEach((e) => {
			flatpickr(e, { enableTime: !0, noCalendar: !0, dateFormat: "H:i" });
		});
	if (
		(document.querySelectorAll(".file-input").forEach((e) => {
			e.addEventListener("change", function () {
				const e = this.closest(".file-input-wrapper").querySelector(".image"),
					t = new FileReader();
				(t.onload = function (t) {
					e.src = t.target.result;
				}),
					t.readAsDataURL(this.files[0]);
			});
		}),
		document.getElementById("product"))
	) {
		tns({ container: "#product", items: 1, startIndex: 1, navContainer: "#product-thumbnails", navAsThumbnails: !0, autoplay: !1, autoplayTimeout: 1e3, swipeAngle: !1, speed: 400, controls: !1 });
	}
	const l = document.getElementById("checkAll");
	l &&
		l.addEventListener("click", function () {
			document.querySelectorAll("input:checkbox").forEach((e) => {
				e !== this && (e.checked = this.checked);
			});
		});
	const c = ["#do", "#progress", "#review", "#done"].map((e) => document.querySelector(e));
	c.some((e) => null !== e) && dragula(c.filter(Boolean));
	const r = document.getElementById("invoice");
	if (r) {
		const e = r.querySelector(".print-link");
		e &&
			e.addEventListener("click", function () {
				$.print("#invoice");
			});
	}
	const s = document.querySelectorAll(".sidebar-nav-fixed a");
	s.forEach((e) => {
		e.addEventListener("click", function (e) {
			if (location.pathname.replace(/^\//, "") === this.pathname.replace(/^\//, "") && location.hostname === this.hostname) {
				const t = document.querySelector(this.hash);
				if (t) {
					e.preventDefault();
					const o = t.getBoundingClientRect().top + window.scrollY - 90;
					window.scroll({ top: o, behavior: "smooth" }), t.focus(), t.is(":focus") || (t.setAttribute("tabindex", "-1"), t.focus());
				}
			}
			s.forEach((e) => {
				e.classList.remove("active");
			}),
				this.classList.add("active");
		});
	});
	const a = document.getElementById("liveAlertPlaceholder"),
		i = document.getElementById("liveAlertBtn");
	if (i && a) {
		i.addEventListener("click", function () {
			!(function (e, t) {
				const o = document.createElement("div");
				(o.innerHTML = `\n        <div class="alert alert-${t} alert-dismissible" role="alert">\n          ${e}\n          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>\n        </div>\n      `), a.appendChild(o);
			})("Nice, you triggered this alert message!", "success");
		});
	}
	document.querySelectorAll("input[name=tags]").forEach((e) => {
		new Tagify(e);
	}),
		"undefined" != typeof feather && feather.replace();
});
