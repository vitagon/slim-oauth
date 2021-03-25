import React from 'react';
import { withRouter } from 'next/router';
import Http from '@/http';
import { NextPageContext } from 'next';

class Authorize extends React.Component<any, any> {
    render() {
        return (
            <>{JSON.stringify(this.props.error)}</>
        );
    }
}

export async function getServerSideProps(ctx: NextPageContext) {
    let data = null;
    try {
        let res = await Http.get(ctx.req.url);
        let data = res.data;
    } catch (e) {
        console.log(e.response.data);
        return { props: { error: e.response.data } }
    }

    return {
        props: {}
    };
}

export default withRouter(Authorize);
