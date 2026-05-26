import { useEffect, useState, useCallback } from "react";
import { debounceFn } from "@/lib/utils";
import TemplateTopBar from "@/components/TemplateTopBar";
import TemplateShow from "@/components/TemplateShow";
import { Button } from "@/components/ui/button";

const StaterTemplate = () => {
  const [reachBottom, richBottom] = useState(false);
  const [loading, setLoading] = useState(true);
  const [allTemplate, setAllTemplate] = useState({});

  const [templateMetaData, setTemplateMetaData] = useState({
    searchKey: "",
    filterKey: "",
    pageNum: 1,
    selectedCategory: "",
  });

  useEffect(() => {
    templateMetaData.allTemplate = allTemplate;
    getAllTemplate(templateMetaData);
  }, [templateMetaData]);

  const getAllTemplate = useCallback(
    debounceFn(async (meta) => {
      setLoading(true);
      try {
        const url = new URL(
          `${theme_template_obj?.base_url}/wcf-theme-templates?theme-cat=${theme_template_obj.theme_cat_id}`
        );

        if (meta.searchKey) {
          url.searchParams.append("s", meta.searchKey);
        }
        if (meta.pageNum) {
          url.searchParams.append("page", meta.pageNum);
          url.searchParams.append("per_page", 20);
        }
        if (meta.filterKey) {
          if (meta.filterKey === "popular") {
            url.searchParams.append("popular", 1);
          } else {
            url.searchParams.append("orderby", "date");
          }
        }
        if (meta.selectedCategory) {
          url.searchParams.append("st-cat", meta.selectedCategory);
        }

        await fetch(url.toString())
          .then((response) => response.json())
          .then((data) => {
            if (meta.pageNum === 1) {
              setAllTemplate(data);
            } else {
             
              const updateData = {
                ...data,
                templates: [...meta?.allTemplate?.templates, ...data.templates],
              };
              setAllTemplate(updateData);
            }
            if (data?.totalpages === meta.pageNum) {
              richBottom(true);
            }
          });
      } catch (error) {        
      } finally {
        setLoading(false);
      }
    }),
    []
  );

  return (
    <>
      {loading && !allTemplate?.templates?.length ? (
        <div className="flex justify-center items-center h-[100vh]">
          <p className="text-lg font-semibold">Loading...</p>
        </div>
      ) : (
        <div className="mx-[31px]">
          <div className="mb-8">
            <TemplateTopBar
              templateMetaData={templateMetaData}
              setTemplateMetaData={setTemplateMetaData}
            />
          </div>
          <div className="mb-10">
            <TemplateShow allTemplate={allTemplate} />
          </div>
        </div>
      )}
      {loading && allTemplate?.templates?.length ? (
        <div className="flex justify-center items-center h-[25vh]">
          <p className="text-lg font-semibold">Loading...</p>
        </div>
      ) : (
        ""
      )}
      {!reachBottom && !loading && allTemplate?.templates?.length ? (
        <div className="flex justify-center items-center mb-10">
          <Button
            onClick={() =>
              setTemplateMetaData({
                ...templateMetaData,
                pageNum: templateMetaData.pageNum + 1,
              })
            }
            className="h-14 px-8"
          >
            Load More Demo
          </Button>
        </div>
      ) : (
        ""
      )}
    </>
  );
};

export default StaterTemplate;
