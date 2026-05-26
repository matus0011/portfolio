import { Button, buttonVariants } from "@/components/ui/button";
import { useTNavigation } from "@/context/app.context";
import { cn } from "@/lib/utils";
import { Dot } from "lucide-react";
import { RiDownloadLine, RiEyeLine } from "react-icons/ri";

const TemplateShow = ({ allTemplate }) => {
  const { setTabKey } = useTNavigation();

  const changeRoute = (value, slug, id) => {
    const url = new URL(window.location.href);
    const pageQuery = url.searchParams.get("page");

    url.search = "";
    url.hash = "";
    url.search = `page=${pageQuery}`;

    url.searchParams.set("tab", value);
    url.searchParams.set("template", slug);
    url.searchParams.set("templateid", id);

    window.history.replaceState({}, "", url);
    setTabKey(value);
  };

  return (
    <div className="w-full">
      {allTemplate?.templates?.length ? (
        <div className="grid md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-x-5 gap-y-8">
          {allTemplate?.templates?.map((template, i) => (
            <div
              key={`all_template-${i}`}
              className="group"
              id={template?.slug}
            >
              <div
                className="rounded-[12px] overflow-hidden border border-[#ededed] bg-no-repeat aspect-[380/330]"
                style={{
                  backgroundImage: `url(${template?.template_preview})`,
                  backgroundSize: "100%",
                }}
              >
                <div className="w-full h-full group-hover:bg-[#0E121B]/40 relative">
                  <div className="w-full h-full hidden group-hover:flex justify-center items-center gap-2">
                    <a
                      href={template?.demo_link}
                      className={cn(
                        buttonVariants({ variant: "general" }),
                        "py-2 ps-3 pe-4"
                      )}
                      target="_blank"
                    >
                      <RiEyeLine size={20} className="mr-2" /> Preview
                    </a>
                    <Button
                      variant="general"
                      className="py-2 ps-3 pe-4"
                      onClick={() =>
                        changeRoute(
                          "required-features",
                          template?.slug,
                          template?.id
                        )
                      }
                    >
                      <RiDownloadLine size={20} className="mr-2" /> Import
                    </Button>
                  </div>
                </div>
              </div>
              <div className="mt-4 flex justify-between">
                <div className="ms-1">
                  <h3 className="text-lg">{template?.title}</h3>
                  <div className="flex gap-1.5 items-center mt-1.5">
                    <div className="flex gap-1 items-center">
                      {template?.categories?.slice(0, 2)?.map((el, i) => (
                        <p key={el + i} className="text-label text-sm">
                          {el}
                          {i === 0 ? ", " : ""}
                        </p>
                      ))}
                    </div>

                    <Dot
                      className="w-2 h-2 text-icon-secondary"
                      strokeWidth={2}
                    />
                    <div className="text-label text-sm flex items-center gap-1">
                      <RiDownloadLine />
                      <p>
                        <span>{template?.downloads}</span> Imports
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="flex justify-center items-center h-[10vh]">
          <p className="text-lg font-semibold">No Item Found</p>
        </div>
      )}
    </div>
  );
};

export default TemplateShow;
