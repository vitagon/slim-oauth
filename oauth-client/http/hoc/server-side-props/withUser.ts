import { getUser } from '@/services/AuthService';
import types from '@/store/auth/types';
import { wrapper } from '@/store';

const withUser = () => async (context) => {
    let { user, removeToken } = await getUser(context.req.headers?.cookie);
    let storeWrapResult: any = await wrapper.getServerSideProps(async ({ store }) => {
        if (user) {
            store.dispatch({
                type: types.SET_USER,
                payload: user
            })
        }
    })(context);

    return {
        props: {
            user,
            removeToken,
            initialState: storeWrapResult.props.initialState
        }
    }
}

export default withUser;
