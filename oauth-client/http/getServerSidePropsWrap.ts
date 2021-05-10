import { wrapper } from '@/store';
import { Store } from 'redux';
import { GetServerSidePropsContext } from 'next-redux-wrapper';
import { GetServerSideProps, GetServerSidePropsResult } from 'next';
import { ParsedUrlQuery } from 'querystring';

function getServerSidePropsWrap(callback) {
    return async (context) => {
        let storeProps : GetServerSidePropsResult<any> = await wrapper.getServerSideProps(callback)(context);
        return {
            ...storeProps,
            props: {
                // @ts-ignore
                ...(storeProps.props),
                msg: 'test',
            }
        }
    }
}

export default getServerSidePropsWrap;