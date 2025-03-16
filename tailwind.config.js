/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./*.{html,js}"], // Ensure it scans your HTML and JS files
  theme: {
    extend: {
      colors: {
        primary: "#6B46C1", // Purple-500
        danger: "#E53E3E", // Red-500
        success: "#3182CE", // Blue-500
        warning: "#D69E2E", // Yellow-500
        textDark: "#4A5568", // Gray-700
        textLight: "#718096", // Gray-600
      },
      spacing: {
        18: "4.5rem",
        22: "5.5rem",
        30: "7.5rem",
      },
      borderRadius: {
        xl: "1rem",
        "2xl": "1.5rem",
      },
      boxShadow: {
        card: "0 10px 20px rgba(0, 0, 0, 0.1)",
      },
    },
  },
  plugins: [],
};
