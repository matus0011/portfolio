// hooks/useObserver.js
import { useEffect } from "react";

const useObserver = (ref, onIntersect, canLoadMore, isLoading) => {
  useEffect(() => {
    if (!ref.current || isLoading || !canLoadMore) return;

    const observer = new IntersectionObserver(
      (entries) => {
        if (entries[0].isIntersecting) {
          onIntersect();
        }
      },
      {
        threshold: 1,
      }
    );

    const el = ref.current;
    observer.observe(el);

    return () => {
      if (el) observer.unobserve(el);
    };
  }, [ref, onIntersect, canLoadMore, isLoading]);
};

export default useObserver;
