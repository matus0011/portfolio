import { lazy } from "react";
import MainLayout from "@/layouts/MainLayout";

const StaterTemplate = lazy(() => import("@/pages/StaterTemplate"));
const RequiredFeatures = lazy(() => import("@/pages/RequiredFeatures"));
const DemoImporting = lazy(() => import("@/pages/DemoImporting"));
const CompleteImport = lazy(() => import("@/pages/CompleteImport"));
const FailImport = lazy(() => import("@/pages/FailImport"));

export const ShowContent = (item) => {
  switch (item.tabKey) {
    case "stater-template":
      return (
        <MainLayout.FirstLayout>
          <StaterTemplate />
        </MainLayout.FirstLayout>
      );
    case "required-features":
      return (
        <MainLayout.SecondLayout>
          <RequiredFeatures />
        </MainLayout.SecondLayout>
      );
    case "demo-importing":
      return (
        <MainLayout.SecondLayout>
          <DemoImporting />
        </MainLayout.SecondLayout>
      );
    case "complete-import":
      return (
        <MainLayout.SecondLayout>
          <CompleteImport />
        </MainLayout.SecondLayout>
      );
    case "fail-import":
      return (
        <MainLayout.SecondLayout>
          <FailImport />
        </MainLayout.SecondLayout>
      );

    default:
      return (
        <MainLayout.FirstLayout>
          <StaterTemplate />
        </MainLayout.FirstLayout>
      );
  }
};
