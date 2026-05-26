import { RiCloseLine, RiSearchLine } from "react-icons/ri";
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Input } from "@/components/ui/input";
import { useEffect, useState } from "react";

const TemplateTopBar = ({ templateMetaData, setTemplateMetaData }) => {
  const [allCategory, setAllCategory] = useState([]);

  useEffect(() => {
    fetch(
      `${theme_template_obj?.base_url}/st-cat?theme_id=${theme_template_obj?.theme_cat_id}`
    )
      .then((response) => response.json())
      .then((data) => {
       
        const updateData = [
          { id: null, name: "All Categories", slug: "all" },
          ...data,
        ];
        setAllCategory(updateData);
      });
  }, []);

  return (
    <div className="flex justify-between items-center gap-5">
      <h3 className="text-xl font-medium">All Templates</h3>
      <div className="flex justify-end items-center gap-2">
        <div className="ml-6">
          <div className="relative">
            <RiSearchLine className="absolute left-3 top-2.5 h-5 w-5 text-icon-secondary" />
            <Input
              value={templateMetaData?.searchKey}
              onChange={(e) =>
                setTemplateMetaData({
                  ...templateMetaData,
                  searchKey: e.target.value,
                  pageNum: 1,
                })
              }
              placeholder="Search Templates"
              className="px-9"
            />
            {templateMetaData?.searchKey ? (
              <RiCloseLine
                onClick={() =>
                  setTemplateMetaData({
                    ...templateMetaData,
                    searchKey: "",
                    pageNum: 1,
                  })
                }
                className="absolute right-3 top-2.5 h-5 w-5 cursor-pointer text-icon-secondary"
              />
            ) : (
              ""
            )}
          </div>
        </div>
        <div>
          <Select
            value={templateMetaData?.selectedCategory}
            onValueChange={(value) =>
              setTemplateMetaData({
                ...templateMetaData,
                selectedCategory: value,
                pageNum: 1,
              })
            }
          >
            <SelectTrigger className="w-[180px] rounded-[10px] h-10">
              <SelectValue placeholder="Category" />
            </SelectTrigger>
            <SelectContent className="w-[180px] rounded-[10px]" align="end">
              {allCategory?.map((category, i) => (
                <SelectItem key={`${category?.slug}-${i}`} value={category?.id}>
                  {category?.name}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
        <div>
          <Select
            value={templateMetaData?.filterKey}
            onValueChange={(value) =>
              setTemplateMetaData({
                ...templateMetaData,
                filterKey: value,
                pageNum: 1,
              })
            }
          >
            <SelectTrigger className="w-[119px] rounded-[10px] h-10">
              <SelectValue placeholder="Filter" />
            </SelectTrigger>
            <SelectContent className="w-[119px] rounded-[10px]" align="end">
              <SelectGroup>
                <SelectItem value="popular">Popular</SelectItem>
                <SelectItem value="latest">Latest</SelectItem>
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
      </div>
    </div>
  );
};

export default TemplateTopBar;
