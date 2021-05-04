import React from 'react';
import DefaultLayout from '@/layouts/default/Default';
import FullPage from '@/layouts/full-page/FullPage';

export const Layouts = {
    default: 'default',
    fullPage: 'fullPage'
}

const layoutsMap = {
    default: DefaultLayout,
    fullPage: FullPage,
};

const withLayout = (Component, layout = 'default') => ({ ...props }) => {

    // to get the text value of the assigned layout of each component
    const Layout = layoutsMap[layout];

    // if we have a registered layout render children with said layout
    if (Layout != null) {
        return (
            <Layout {...props}>
                <Component {...props}/>
            </Layout>
        );
    }

    // if not render children with fragment
    return (
        <DefaultLayout {...props}>
            <Component {...props}/>
        </DefaultLayout>
    );
}

export default withLayout;
