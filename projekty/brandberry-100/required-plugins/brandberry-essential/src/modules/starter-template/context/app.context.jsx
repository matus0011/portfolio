import { createContext, useCallback, useContext, useReducer } from "react";

const initialState = {
  tabKey: "",
};

const reducer = (state, action) => {
  switch (action.type) {
    case "setTabKey":
      return { ...state, tabKey: action.value };

    default:
      throw new Error();
  }
};

const useMainContext = (state) => {
  const [mainState, dispatch] = useReducer(reducer, state);

  const setTabKey = useCallback((data) => {
    dispatch({
      type: "setTabKey",
      value: data,
    });
  }, []);

  return {
    mainState,

    setTabKey,
  };
};

export const AppContext = createContext({
  mainState: initialState,

  setTabKey: () => {},
});

export const AppContextProvider = ({ children }) => {
  return (
    <AppContext.Provider value={useMainContext(initialState)}>
      {children}
    </AppContext.Provider>
  );
};

export const useTNavigation = () => {
  const {
    mainState: { tabKey },
    setTabKey,
  } = useContext(AppContext);

  return { tabKey, setTabKey };
};