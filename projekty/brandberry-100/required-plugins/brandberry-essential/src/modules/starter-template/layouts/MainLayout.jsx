import { ShowContent } from "@/config/showFullContent";
import { useTNavigation } from "@/context/app.context";
import { useEffect, useState, Suspense } from "react";

const MainLayout = () => {
  const { tabKey, setTabKey } = useTNavigation();

  useEffect(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const tabValue = urlParams.get("tab");
    if (tabValue) {
      setTabKey(tabValue);
    }
  }, []);

  return (
    <div className="wcftst-2025-wrapper">
      <div className="wcftst-2025-style">
        <Suspense
          fallback={
            <div className="flex justify-center items-center h-screen">
              <p className="text-lg font-semibold">Loading...</p>
            </div>
          }
        >
          {ShowContent({ tabKey })}
        </Suspense>
      </div>
    </div>
  );
};

MainLayout.FirstLayout = ({ children }) => {
  return (
    <div className="bg-background p-8">
      <div>{children}</div>
    </div>
  );
};

MainLayout.SecondLayout = ({ children }) => {
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const timer = setTimeout(() => {
      setLoading(false);
    }, 1000);

    return () => clearTimeout(timer);
  }, []);

  return (
    <>
      {loading ? (
        <div className="flex justify-center items-center h-screen">
          <p className="text-lg font-semibold">Loading...</p>
        </div>
      ) : (
        <div className="bg-background p-8">
          <div className="flex justify-center items-center min-h-[calc(100vh-85px)] py-5">
            {children}
          </div>
        </div>
      )}
    </>
  );
};

export default MainLayout;
