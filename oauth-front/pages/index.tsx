import React from 'react';
import DefaultLayout from '@/layouts/default/Default';
import { wrapper } from '@/store';
import { connect } from 'react-redux';
import { getUser } from '@/services/AuthService';
import types from '@/store/auth/types';
import { bindActionCreators } from 'redux';

class Home extends React.Component<any, any> {
    render() {
        return (
            <DefaultLayout>
                <div className="row">
                    <div className="col-md-12">
                        Home page accessible without authentication
                        <div>
                            User: {JSON.stringify(this.props.reduxUser)}
                        </div>
                        <button onClick={() => this.props.setMessage('test')}>Set message</button>
                    </div>
                </div>
            </DefaultLayout>
        );
    }
}

export const getServerSideProps = wrapper.getServerSideProps(
    async ({ store , req}) => {
        let user = await getUser(req.headers.cookie);

        if (user) {
            store.dispatch({
                type: types.SET_USER,
                payload: user
            })
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
