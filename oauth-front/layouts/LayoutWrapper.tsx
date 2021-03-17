import DefaultLayout from "./default/Default";
import FullPage from "@/layouts/full-page/FullPage";

const layouts = {
  default: DefaultLayout,
  fullPage: FullPage,
};

const LayoutWrapper = (props) => {
  // to get the text value of the assigned layout of each component
  const Layout = layouts[props.children[1].type.layout];

  // if we have a registered layout render children with said layout
  if (Layout != null) {
    return <Layout {...props}>{props.children}</Layout>;
  }

  // if not render children with fragment
  return <DefaultLayout {...props}>{props.children}</DefaultLayout>;
};

export default LayoutWrapper;