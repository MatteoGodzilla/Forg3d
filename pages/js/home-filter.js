document.addEventListener("DOMContentLoaded", function () {
	const toggle = document.getElementById("filterToggle");
    const dropdown = document.getElementById("filterDropdown");

	toggle.addEventListener("click", function (e) {
		e.stopPropagation(); // Previene la chiusura immediata
		dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
	});

    // Chiude il dropdown se clicchi fuori
	document.addEventListener("click", function (e) {
		if (!dropdown.contains(e.target) && !toggle.contains(e.target)) {
			dropdown.style.display = "none";
		}
	});
});