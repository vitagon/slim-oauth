import React from 'react';
import DefaultLayout from '@/layouts/default/Default';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import combine from '@/http/hoc/server-side-props/combine';
import { GetServerSidePropsContext } from 'next';
import withUser from '@/http/hoc/server-side-props/withUser';
import withCookies from '@/http/hoc/server-side-props/withCookies';

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
                    </div>
                </div>
            </DefaultLayout>
        );
    }
}

export const getServerSideProps = combine(
    withUser(),
    async (context: GetServerSidePropsContext) => {
        return {
            props: {
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
