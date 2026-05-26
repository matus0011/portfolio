import { AppContextProvider } from "./context/app.context";
import MainLayout from "./layouts/MainLayout";
import "./index.css";
import { Toaster } from "./components/ui/sonner";

const bodyElement = document.body;
if (bodyElement) {
  const customDiv = document.createElement("div");
  customDiv.id = "wcf--theme-starter-template--toast";
  bodyElement.appendChild(customDiv);
}
document.body.classList.add("wcftst-2025");

wp.element.render(
  <AppContextProvider>
    <MainLayout />
  </AppContextProvider>,
  document.getElementById("wcf-demo-importer-starter")
);

wp.element.render(
  <Toaster />,
  document.getElementById("wcf--theme-starter-template--toast")
);
