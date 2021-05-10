import React from 'react';
import DefaultLayout from '@/layouts/default/Default';
import { wrapper } from '@/store';
import { connect } from 'react-redux';
import { getUser } from '@/services/AuthService';
import types from '@/store/auth/types';
import { bindActionCreators } from 'redux';
import CookieHolder from '@/http/cookieHolder';
import Cookies from 'cookies';
import getServerSidePropsWrap from '@/http/getServerSidePropsWrap';

class Home extends React.Component<any, any> {
    componentDidMount() {
        let o = this.props;
        debugger;
    }

    render() {
        return (
            <DefaultLayout>
                <div className="row">
                    <div className="col-md-12">
                        Home page accessible without authentication
                        <div>
                            User: {JSON.stringify(this.props.reduxUser)}
                        </div>
                    </div>
                </div>
            </DefaultLayout>
        );
    }
}

// export const getServerSideProps = wrapper.getServerSideProps(
export const getServerSideProps = getServerSidePropsWrap(
    async ({ store , req, res}) => {
        CookieHolder.cookies = new Cookies(req, res);
        let user = await getUser(req.headers.cookie);

        if (user) {
            store.dispatch({
                type: types.SET_USER,
                payload: user
            })
        }

        return {
            props: {
                comp: 'faa'
            }
        }
    }
)

const mapStateToProps = (state) => ({
    reduxUser: state.AuthReducer.user
});

const mapDispatchToProps = (dispatch) => bindActionCreators({
    setMessage: (msg) => (dispatch) => dispatch({ type: 'SET_MESSAGE', payload: msg })
}, dispatch);

export default connect(mapStateToProps, mapDispatchToProps)(Home);
