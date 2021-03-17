import App from "next/app";
import { wrapper } from '@/store';
import LayoutWrapper from "@/layouts/LayoutWrapper";

import 'bootstrap/dist/css/bootstrap.min.css';
import '@/styles/globals.css';
import NextNprogress from '@/components/next-nprogress/NextNProgress';
import '@/extensions/StringExtension.ts';

class MyApp extends App {
  static async getInitialProps({ Component, ctx }) {
    return {
      pageProps: {
        // Call page-level getInitialProps
        ...(Component.getInitialProps
          ? await Component.getInitialProps(ctx)
          : {})
      }
    };
  }

  render() {
    const { Component, pageProps } = this.props;

    return (
      <>
        <LayoutWrapper {...pageProps}>
          <NextNprogress
            color="#29D"
            startPosition={0.3}
            stopDelayMs={200}
            height="3"
          />
          <Component {...pageProps} />
        </LayoutWrapper>
      </>
    );
  }
}

export default wrapper.withRedux(MyApp);