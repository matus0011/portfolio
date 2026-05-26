const tailwindStarterTemplate = require("./tailwind.starterTemplate.config.js");
const tailwindPageImport = require("./tailwind.pageImport.config.js");

module.exports = {
  plugins: {
    "postcss-nested": {},
    tailwindcss: { tailwindStarterTemplate },
    tailwindcss: { tailwindPageImport },
    autoprefixer: {},
  },
};