import { GetServerSidePropsContext } from 'next';
import RequestContext from '@/http/RequestContext';
import ResponseContext from '@/http/ResponseContext';
import CookieHolder from '@/http/CookieHolder';

const combine = (...funcArr) => {
    return async (context: GetServerSidePropsContext) => {
        let finalObj: any = {};

        RequestContext.init(context.req);
        ResponseContext.init(context.res);
        CookieHolder.init(context.req, context.res);

        for (const func of funcArr) {
            let hocResult = await func(context);
            if (!hocResult) {
                continue;
            }

            if (typeof finalObj.props !== 'undefined' && typeof  hocResult.props !== 'undefined') {
                let props = mergeProps(finalObj.props, hocResult.props);
                finalObj = { ...finalObj, ...hocResult, props };
            } else {
                finalObj = { ...finalObj, ...hocResult };
            }
        }

        return finalObj;
    };
}

const mergeProps = (props, hocProps) => {
    let result = {
        ...props,
        ...hocProps,
    }

    if (typeof props.initialState !== 'undefined' && typeof hocProps.initialState !== 'undefined') {
        props.initialState = mergeState(props.initialState, hocProps.initialState);
    }

    return result;
}

/**
 * This is not hydration, this will be executed only on server side
 *
 * Every next HOC will override <Name>Reducer state, if it was defined earlier
 * If we want to preserve specific properties of specific reducer, then we need to do it here
 *
 * @param storeState
 * @param storeHOCState
 */
const mergeState = (storeState, storeHOCState) => {
    let store = {
        ...storeState,
        ...storeHOCState,
    }

    if (storeHOCState?.AuthReducer?.user) {
        // override user in store if hoc has it
        store.AuthReducer.user = storeHOCState.AuthReducer.user;
    }

    return store;
}

export default combine;
