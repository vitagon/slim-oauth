import React from 'react';
import styles from '@/styles/Login.module.scss';
import { withRouter } from 'next/router';
import FullPage from '@/layouts/full-page/FullPage';

interface State {
    form: {
        login: string,
        password: string,
    },
    errors: {
        login: string,
        password: string,
    }
}

class Login extends React.Component<any, State> {

    constructor(props) {
        super(props);
    }

    login = async () => {
        this.props.router.push({
            pathname: 'http://company.loc/oauth/authorize',
            query: {
                response_type: 'code',
                client_id: '899',
                redirect_uri: 'http://client.loc/callback',
                scope: '*'
            }
        });
    };

    changeFormVal = (e) => {
        let name = e.target.name;
        let value = e.target.value;

        this.setState({
            ...this.state,
            form: Object.assign({}, this.state.form, {[name]: value})
        });
    };

    render() {
        return (
            <FullPage>
                <div className={styles.loginRow}>
                    <div className="py-4 px-4">
                        <a href="/" className={styles.logo}>
                            <span>Company</span>
                        </a>

                        <p className={styles.signInText}>Вход в аккаунт</p>

                        <form method="POST" action="/login">

                            <div className="mt-8">
                                <button
                                    type="button"
                                    className="btn btn-block btn-primary"
                                    onClick={this.login}
                                >
                                    Войти
                                </button>
                            </div>
                        </form>
                    </div>

                    <div className={styles.bottomBlock}>
                        <span className="text-gray-600 text-sm">Нет аккаунта? </span>

                        <a href="/register" className="mx-2">
                            Создать
                        </a>
                    </div>
                </div>
            </FullPage>
        );
    }
}

export async function getServerSideProps(ctx) {
    // let data = null;
    // console.log(ctx.req.headers);
    // try {
    //     let res = await Http.get('/profile', {
    //         headers: {cookie: ctx.req.headers.cookie}
    //     });
    //     data = res.data;
    // } catch (e) {
    //     console.log(e);
    // }
    //
    // if (data && data.user) {
    //     let {res} = ctx;
    //     res.setHeader('Location', '/profile');
    //     res.statusCode = 302;
    //     return;
    // }

    return {
        props: {}
    };
}

export default withRouter(Login);
