document.addEventListener("DOMContentLoaded", function () {
  const themeSelect = document.getElementById("themeSelect");

  // Load the saved theme from localStorage
  const savedTheme = localStorage.getItem("theme") || "light";
  document.body.classList.add(`bg-${savedTheme}`);

  themeSelect.value = savedTheme;

  // Listen for theme changes
  themeSelect.addEventListener("change", function () {
    // Remove the current theme class
    document.body.classList.remove("light-theme", "dark-theme");

    // Add the selected theme class
    const selectedTheme = themeSelect.value;
    document.body.classList.add(`${selectedTheme}-theme`);
    const container = document.querySelector(".container");
    container.classList.add(`${selectedTheme}-theme`);
    // Save the selected theme to localStorage
    localStorage.setItem("theme", selectedTheme);
  });
});
